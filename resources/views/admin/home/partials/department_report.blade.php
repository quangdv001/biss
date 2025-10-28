<!-- Báo cáo phòng ban -->
<div class="filter-card">
    <h5 class="mb-4"><i class="flaticon2-filter"></i> Bộ lọc</h5>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Phòng ban: <span class="text-danger">*</span></label>
                <select class="form-control" id="department_role">
                    <option value="">-- Chọn phòng ban --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Nhân sự:</label>
                <select class="form-control" id="department_admin">
                    <option value="">Tất cả</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Từ ngày:</label>
                <input type="date" class="form-control" id="department_start_time" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Đến ngày:</label>
                <input type="date" class="form-control" id="department_end_time" value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-primary btn-block" onclick="loadDepartmentReport()">
                    <i class="flaticon2-reload"></i> Xem báo cáo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabs cho các loại báo cáo -->
<ul class="nav nav-pills nav-pills-sm nav-dark-75" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#dept_task_report">
            <span class="nav-text">Báo cáo theo Task</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#dept_project_report">
            <span class="nav-text">Báo cáo theo Dự án</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#dept_expired_report">
            <span class="nav-text">Dự án sắp hết hạn</span>
        </a>
    </li>
</ul>

<div class="separator separator-solid my-5"></div>

<div class="tab-content">
    <!-- Tab báo cáo theo Task -->
    <div class="tab-pane fade show active" id="dept_task_report" role="tabpanel">
        <div id="department_report_table">
            <div class="text-center p-5">
                <p class="text-muted">Vui lòng chọn phòng ban và nhấn "Xem báo cáo"</p>
            </div>
        </div>
    </div>

    <!-- Tab báo cáo theo Dự án -->
    <div class="tab-pane fade" id="dept_project_report" role="tabpanel">
        <div id="department_project_table">
            <div class="text-center p-5">
                <p class="text-muted">Vui lòng chọn phòng ban và nhấn "Xem báo cáo"</p>
            </div>
        </div>
    </div>

    <!-- Tab dự án sắp hết hạn -->
    <div class="tab-pane fade" id="dept_expired_report" role="tabpanel">
        <div id="department_expired_table">
            <div class="text-center p-5">
                <p class="text-muted">Vui lòng chọn phòng ban và nhấn "Xem báo cáo"</p>
            </div>
        </div>
    </div>
</div>
