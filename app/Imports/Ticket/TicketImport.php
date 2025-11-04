<?php

namespace App\Imports\Ticket;

use App\Repo\TicketRepo;
use App\Repo\AdminRepo;
use App\Repo\ProjectRepo;
use App\Repo\GroupRepo;
use App\Repo\PhaseRepo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TicketImport implements ToCollection, WithHeadingRow, WithMultipleSheets
{
    protected $projectId;
    protected $groupId;
    protected $phaseId;
    protected $adminIdC;
    protected $sheetIndex;

    public function __construct($projectId, $groupId, $phaseId, $adminIdC, $sheetIndex = null)
    {
        $this->projectId = $projectId;
        $this->groupId = $groupId;
        $this->phaseId = $phaseId;
        $this->adminIdC = $adminIdC;
        $this->sheetIndex = $sheetIndex;
    }

    /**
     * Specify which row contains the headings
     */
    public function headingRow(): int
    {
        return 2; // Use row 2 as the heading row
    }

    /**
     * Specify which sheets to import
     */
    public function sheets(): array
    {
        // If a specific sheet is selected, only import that sheet
        if ($this->sheetIndex !== null) {
            return [
                $this->sheetIndex => $this
            ];
        }

        // Otherwise import the first sheet (index 0)
        return [
            0 => $this
        ];
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        if ($collection->count()) {
            $ticketRepo = app(TicketRepo::class);
            $adminRepo = app(AdminRepo::class);

            // Get all admins for reference - key by username (lowercase for case-insensitive matching)
            $adminsCollection = $adminRepo->get([]);
            $admins = [];
            foreach ($adminsCollection as $admin) {
                $admins[strtolower(trim($admin->username))] = $admin->id;
            }

            $data = [];
            $ticketAdminRelations = []; // Store ticket-admin relationships

            foreach ($collection as $index => $row) {
                // Skip empty rows
                if (empty($row['chu_de'])) {
                    continue;
                }

                // Parse deadline
                $deadlineTime = null;
                if (!empty($row['deadline'])) {
                    try {
                        // Check if it's an Excel date serial number
                        if (is_numeric($row['deadline'])) {
                            $deadlineTime = Date::excelToDateTimeObject($row['deadline'])->getTimestamp();
                        } else {
                            // Try to parse as string date
                            $deadlineTime = strtotime($row['deadline']);
                        }
                        // Set to end of day
                        if ($deadlineTime) {
                            $deadlineTime = strtotime('tomorrow', $deadlineTime) - 1;
                        }
                    } catch (\Exception $e) {
                        $deadlineTime = null;
                    }
                }

                // Parse người xử lý (handler/assigned admin)
                $assignedAdminIds = [];
                if (!empty($row['nguoi_xu_ly'])) {
                    // Split by comma or semicolon to support multiple admins
                    $adminNames = preg_split('/[,;]+/', $row['nguoi_xu_ly']);

                    foreach ($adminNames as $adminName) {
                        $adminName = strtolower(trim($adminName));
                        if (!empty($adminName) && isset($admins[$adminName])) {
                            $assignedAdminIds[] = $admins[$adminName];
                        }
                    }

                    // Remove duplicates
                    $assignedAdminIds = array_unique($assignedAdminIds);
                }

                $ticketData = [
                    'name' => $row['chu_de'] ?? '',
                    'description' => $row['mo_ta'] ?? '',
                    'input' => $row['khach_duyet'] ?? '',
                    'output' => $row['san_pham'] ?? '',
                    'note' => $row['ghi_chu'] ?? '',
                    'status' => 0, // Default to not completed
                    'created_time' => time(),
                    'deadline_time' => $deadlineTime,
                    'complete_time' => null,
                    'qty' => 1, // Default quantity
                    'priority' => 2, // Default priority (medium)
                    'admin_id_c' => $this->adminIdC,
                    'project_id' => $this->projectId,
                    'group_id' => $this->groupId,
                    'phase_id' => $this->phaseId,
                    'parent_id' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $data[] = $ticketData;

                // Store admin relationships for this ticket (using index as temporary key)
                if (!empty($assignedAdminIds)) {
                    $ticketAdminRelations[$index] = $assignedAdminIds;
                }
            }

            if (!empty($data)) {
                // Insert tickets in chunks and sync admin relationships
                $insertedTickets = [];

                foreach (array_chunk($data, 100) as $chunkIndex => $chunk) {
                    // Calculate the starting index for this chunk
                    $chunkStartIndex = $chunkIndex * 100;

                    foreach ($chunk as $localIndex => $ticketData) {
                        $originalIndex = $chunkStartIndex + $localIndex;

                        // Create ticket
                        $ticket = $ticketRepo->create($ticketData);

                        // Sync admins if any were assigned
                        if (isset($ticketAdminRelations[$originalIndex]) && !empty($ticketAdminRelations[$originalIndex])) {
                            $ticket->admin()->sync($ticketAdminRelations[$originalIndex]);
                        }
                    }
                }
            }
        }
    }
}
