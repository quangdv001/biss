<!-- Báo cáo dự án -->
<div class="row mb-5">
    <div class="col-12">
        <h5 class="mb-4"><i class="flaticon2-pie-chart-3"></i> Báo cáo tình trạng dự án</h5>
    </div>
</div>

<!-- Thống kê tổng quan -->
<div class="row mb-5">
    <div class="col-md-3">
        <div class="card card-stats bg-light-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-success mr-3">
                        <span class="symbol-label">
                            <i class="flaticon2-rocket text-success icon-xl"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 font-size-sm">Đang hoạt động</h6>
                        <h3 class="stat-number text-success mb-0" id="active_projects_count">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-light-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-warning mr-3">
                        <span class="symbol-label">
                            <i class="flaticon2-warning text-warning icon-xl"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 font-size-sm">Dự án trễ</h6>
                        <h3 class="stat-number text-warning mb-0" id="late_projects_count">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-light-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-info mr-3">
                        <span class="symbol-label">
                            <i class="flaticon2-hourglass text-info icon-xl"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 font-size-sm">Có task chưa làm</h6>
                        <h3 class="stat-number text-info mb-0" id="pending_projects_count">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-light-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-danger mr-3">
                        <span class="symbol-label">
                            <i class="flaticon2-alarm text-danger icon-xl"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 font-size-sm">Sắp hết hạn</h6>
                        <h3 class="stat-number text-danger mb-0" id="expiring_projects_count">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dự án đang hoạt động -->
<div id="projectReportAccordion">
    <div class="card card-custom gutter-b">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse_active_projects" style="cursor: pointer;">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-rocket text-success"></i> Dự án đang hoạt động
                    <span class="badge badge-success badge-lg ml-2" id="active_projects_badge">0</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <span class="svg-icon svg-icon-md">
                    <i class="ki ki-arrow-down"></i>
                </span>
            </div>
        </div>
        <div id="collapse_active_projects" class="collapse" data-parent="#projectReportAccordion">
            <div class="card-body" id="active_projects_content">
                <div class="text-center p-5">
                    <p class="text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dự án đã hoàn thành nhưng còn task chưa làm -->
    <div class="card card-custom gutter-b">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse_late_projects" style="cursor: pointer;">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-warning text-warning"></i> Dự án trễ (Đã hoàn thành nhưng còn task chưa làm)
                    <span class="badge badge-warning badge-lg ml-2" id="late_projects_badge">0</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <span class="svg-icon svg-icon-md">
                    <i class="ki ki-arrow-down"></i>
                </span>
            </div>
        </div>
        <div id="collapse_late_projects" class="collapse" data-parent="#projectReportAccordion">
            <div class="card-body" id="late_projects_content">
                <div class="text-center p-5">
                    <p class="text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dự án đang có task chưa hoàn thành -->
    <div class="card card-custom gutter-b">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse_pending_projects" style="cursor: pointer;">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-hourglass text-info"></i> Dự án đang có task chưa hoàn thành
                    <span class="badge badge-info badge-lg ml-2" id="pending_projects_badge">0</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <span class="svg-icon svg-icon-md">
                    <i class="ki ki-arrow-down"></i>
                </span>
            </div>
        </div>
        <div id="collapse_pending_projects" class="collapse" data-parent="#projectReportAccordion">
            <div class="card-body" id="pending_projects_content">
                <div class="text-center p-5">
                    <p class="text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dự án sắp hết hạn -->
    <div class="card card-custom gutter-b">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse_expiring_projects" style="cursor: pointer;">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-alarm text-danger"></i> Dự án sắp hết hạn (Còn 7 ngày)
                    <span class="badge badge-danger badge-lg ml-2" id="expiring_projects_badge">0</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <span class="svg-icon svg-icon-md">
                    <i class="ki ki-arrow-down"></i>
                </span>
            </div>
        </div>
        <div id="collapse_expiring_projects" class="collapse" data-parent="#projectReportAccordion">
            <div class="card-body" id="expiring_projects_content">
                <div class="text-center p-5">
                    <p class="text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>
</div>

