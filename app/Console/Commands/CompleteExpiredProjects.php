<?php

namespace App\Console\Commands;

use App\Repo\ProjectRepo;
use Illuminate\Console\Command;

class CompleteExpiredProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:complete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chuyển các dự án đang hoạt động (status=1) nhưng đã hết hạn (expired_time) sang trạng thái Hoàn thành (status=2)';

    /**
     * Execute the console command.
     */
    public function handle(ProjectRepo $projectRepo)
    {
        $count = $projectRepo->completeExpiredProjects();

        $this->info("Đã chuyển {$count} dự án hết hạn sang trạng thái Hoàn thành.");

        return self::SUCCESS;
    }
}
