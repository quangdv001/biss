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
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TicketImport implements ToCollection, WithHeadingRow
{
    protected $projectId;
    protected $groupId;
    protected $phaseId;
    protected $adminIdC;

    public function __construct($projectId, $groupId, $phaseId, $adminIdC)
    {
        $this->projectId = $projectId;
        $this->groupId = $groupId;
        $this->phaseId = $phaseId;
        $this->adminIdC = $adminIdC;
    }

    /**
     * Specify which row contains the headings
     */
    public function headingRow(): int
    {
        return 2; // Use row 2 as the heading row
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        if ($collection->count()) {
            $ticketRepo = app(TicketRepo::class);
            $adminRepo = app(AdminRepo::class);

            // Get all admins for reference
            $admins = $adminRepo->getAdmin()->keyBy('username');

            $data = [];
            foreach ($collection as $row) {
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

                $data[] = [
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
            }

            if (!empty($data)) {
                foreach (array_chunk($data, 1000) as $chunk) {
                    $ticketRepo->insert($chunk);
                }
            }
        }
    }
}
