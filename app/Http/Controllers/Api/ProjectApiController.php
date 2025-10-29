<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repo\ProjectRepo;
use App\Repo\GroupRepo;
use App\Repo\TicketRepo;
use App\Repo\PhaseRepo;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    private $projectRepo;
    private $groupRepo;
    private $ticketRepo;
    private $phaseRepo;

    public function __construct(ProjectRepo $projectRepo, GroupRepo $groupRepo, TicketRepo $ticketRepo, PhaseRepo $phaseRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->groupRepo = $groupRepo;
        $this->ticketRepo = $ticketRepo;
        $this->phaseRepo = $phaseRepo;
    }

    /**
     * Get list of projects with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->get('limit', 20);
            $name = $request->get('name', '');
            $field = $request->get('field', '');
            $status = (int) $request->get('status', 0);
            $type = $request->get('type', 0);
            $orderBy = $request->get('order', 'id');

            $condition = [];

            // Filter by status and expired_time
            if($status > 0){
                if ($status == 1) {
                    $condition['status'] = $status;
                    $condition[] = ['expired_time', '>=', time()];
                } else {
                    $condition['status'] = [1, 2];
                    $condition[] = ['expired_time', '<', time()];
                }
            }

            // Filter by type
            if($type > 0){
                $condition['type'] = $type;
            }

            // Filter by name
            if(!empty($name)){
                $condition['name'] = $name;
            }

            // Filter by field
            if(!empty($field)){
                $condition['field'] = $field;
            }

            $user = $request->user();
            $isAdmin = $user->hasRole(['super_admin','account']);

            if($isAdmin){
                $data = $this->projectRepo->paginate($condition, $limit, [$orderBy => 'DESC'], ['planer', 'executive', 'admin', 'ticket']);
            } else {
                $data = $this->projectRepo->search($condition, $limit, $user->id, $orderBy);
            }

            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'pagination' => [
                    'total' => $data->total(),
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of groups by project_id
     *
     * @param Request $request
     * @param int $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function groups(Request $request, $projectId)
    {
        try {
            $project = $this->projectRepo->first(['id' => $projectId]);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found'
                ], 404);
            }

            // Check permission
            $user = $request->user();
            $isAdmin = $user->hasRole(['super_admin', 'account']);

            if (!$isAdmin && !in_array($user->id, $project->admin->pluck('id')->toArray())) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this project'
                ], 403);
            }

            // Get latest phase or use phase_id from request
            $phaseId = $request->get('phase_id', 0);

            if ($phaseId <= 0) {
                // Get latest phase if not specified
                $latestPhase = $this->phaseRepo->first(['project_id' => $projectId], ['id' => 'DESC']);
                $phaseId = $latestPhase ? $latestPhase->id : 0;
            }

            $groups = $this->groupRepo->get(['project_id' => $projectId], ['id' => 'ASC'], ['role']);

            return response()->json([
                'success' => true,
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description
                ],
                'phase_id' => $phaseId,
                'data' => $groups
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching groups',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of tickets by group_id
     *
     * @param Request $request
     * @param int $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function tickets(Request $request, $groupId)
    {
        try {
            $group = $this->groupRepo->first(['id' => $groupId], [], ['project']);

            if (!$group) {
                return response()->json([
                    'success' => false,
                    'message' => 'Group not found'
                ], 404);
            }

            // Check permission
            $user = $request->user();
            $isAdmin = $user->hasRole(['super_admin', 'account']);

            if (!$isAdmin && !in_array($user->id, $group->project->admin->pluck('id')->toArray())) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this group'
                ], 403);
            }

            // Get latest phase or use phase_id from request
            $phaseId = $request->get('phase_id', 0);

            if ($phaseId <= 0) {
                // Get latest phase if not specified
                $latestPhase = $this->phaseRepo->first(['project_id' => $group->project_id], ['id' => 'DESC']);
                $phaseId = $latestPhase ? $latestPhase->id : 0;
            }

            $condition = [
                'group_id' => $groupId
            ];

            if ($phaseId > 0) {
                $condition['phase_id'] = $phaseId;
            }

            $tickets = $this->ticketRepo->get($condition, ['deadline_time' => 'asc'], ['admin', 'creator', 'child.admin'])
                ->map(function ($ticket) {
                    if ($ticket->status == 0) {
                        if ($ticket->deadline_time > time()) {
                            $ticket->status_label = 'New';
                            $ticket->status_class = 'success';
                        } else {
                            $ticket->status_label = 'Overdue';
                            $ticket->status_class = 'danger';
                        }
                    } else {
                        if ($ticket->deadline_time > $ticket->complete_time) {
                            $ticket->status_label = 'Completed';
                            $ticket->status_class = 'success';
                        } else {
                            $ticket->status_label = 'Completed Late';
                            $ticket->status_class = 'danger';
                        }
                    }
                    return $ticket;
                });

            return response()->json([
                'success' => true,
                'group' => [
                    'id' => $group->id,
                    'name' => $group->name,
                    'project_id' => $group->project_id,
                    'project_name' => $group->project->name
                ],
                'phase_id' => $phaseId,
                'data' => $tickets
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching tickets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ticket detail by id
     *
     * @param Request $request
     * @param int $ticketId
     * @return \Illuminate\Http\JsonResponse
     */
    public function ticketDetail(Request $request, $ticketId)
    {
        try {
            $ticket = $this->ticketRepo->first(['id' => $ticketId], [], ['admin', 'creator', 'project', 'group', 'child.admin']);

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found'
                ], 404);
            }

            // Check permission
            $user = $request->user();
            $isAdmin = $user->hasRole(['super_admin', 'account']);
            $isAssigned = in_array($user->id, $ticket->admin->pluck('id')->toArray());

            if (!$isAdmin && !$isAssigned && $ticket->admin_id_c != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this ticket'
                ], 403);
            }

            // Add status info
            if ($ticket->status == 0) {
                if ($ticket->deadline_time > time()) {
                    $ticket->status_label = 'New';
                    $ticket->status_class = 'success';
                } else {
                    $ticket->status_label = 'Overdue';
                    $ticket->status_class = 'danger';
                }
            } else {
                if ($ticket->deadline_time > $ticket->complete_time) {
                    $ticket->status_label = 'Completed';
                    $ticket->status_class = 'success';
                } else {
                    $ticket->status_label = 'Completed Late';
                    $ticket->status_class = 'danger';
                }
            }

            return response()->json([
                'success' => true,
                'data' => $ticket
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching ticket detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update ticket
     *
     * @param Request $request
     * @param int $ticketId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTicket(Request $request, $ticketId)
    {
        try {
            $ticket = $this->ticketRepo->first(['id' => $ticketId], [], ['admin', 'project']);

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found'
                ], 404);
            }

            // Check permission
            $user = $request->user();
            $isAdmin = $user->hasRole(['super_admin', 'account']);
            $isCreator = $ticket->admin_id_c == $user->id;
            $isAssigned = in_array($user->id, $ticket->admin->pluck('id')->toArray());

            if (!$isAdmin && !$isCreator && !$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update this ticket'
                ], 403);
            }

            // Validate request
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string|nullable',
                'note' => 'sometimes|string|nullable',
                'input' => 'sometimes|string|nullable',
                'output' => 'sometimes|string|nullable',
                'qty' => 'sometimes|integer|min:1',
                'priority' => 'sometimes|integer|in:1,2,3',
                'deadline_time' => 'sometimes|date',
                'status' => 'sometimes|boolean',
                'admin' => 'sometimes|array'
            ]);

            $params = $request->only(['name', 'description', 'note', 'input', 'output', 'qty', 'priority', 'status']);
            // Convert deadline_time to timestamp
            if ($request->has('deadline_time')) {
                $deadlineTime = strtotime($request->deadline_time);

                // Check if deadline exceeds project expired_time
                if ($ticket->project && $ticket->project->expired_time && $deadlineTime > $ticket->project->expired_time) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Deadline cannot exceed project end date',
                        'project_expired_time' => date('Y-m-d', $ticket->project->expired_time)
                    ], 422);
                }

                $params['deadline_time'] = $deadlineTime;
            }

            // Convert status to integer
            if ($request->has('status')) {
                $params['status'] = $request->status ? 1 : 0;

                // Set complete_time if status is completed
                if ($params['status'] == 1 && $ticket->status == 0) {
                    $params['complete_time'] = time();
                } elseif ($params['status'] == 0 && $ticket->status == 1) {
                    $params['complete_time'] = null;
                }
            }

            // Update ticket
            $updated = $this->ticketRepo->update($ticket, $params);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update ticket'
                ], 500);
            }

            // Update assigned admins if provided
            if ($request->has('admin') && ($isAdmin || $isCreator)) {
                $ticket->admin()->sync($request->admin);
            }

            // Reload ticket with relations
            $ticket = $this->ticketRepo->first(['id' => $ticketId], [], ['admin', 'creator', 'project', 'group']);

            return response()->json([
                'success' => true,
                'message' => 'Ticket updated successfully',
                'data' => $ticket
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
