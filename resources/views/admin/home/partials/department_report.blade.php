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

<script>
// Load admin list when role changes
$('#department_role').on('change', function() {
    const roleId = $(this).val();
    if (roleId) {
        $.ajax({
            url: '{{ route("admin.role.report") }}',
            method: 'GET',
            data: { id: roleId },
            success: function(response) {
                if (response.success && response.admin) {
                    let options = '<option value="">Tất cả</option>';
                    response.admin.forEach(admin => {
                        options += `<option value="${admin.id}">${admin.username}</option>`;
                    });
                    $('#department_admin').html(options);
                }
            }
        });
    } else {
        $('#department_admin').html('<option value="">Tất cả</option>');
    }
});

// Load project report and expired report when switching tabs
$('a[href="#dept_project_report"]').on('shown.bs.tab', function() {
    loadDepartmentProjectReport();
});

$('a[href="#dept_expired_report"]').on('shown.bs.tab', function() {
    loadDepartmentExpiredReport();
});

function loadDepartmentProjectReport() {
    const roleId = $('#department_role').val();
    const startTime = $('#department_start_time').val();
    const endTime = $('#department_end_time').val();
    const adminId = $('#department_admin').val();

    if (!roleId) {
        init.showNoty('Vui lòng chọn phòng ban!', 'warning');
        return;
    }

    $.ajax({
        url: '{{ route("admin.role.report2") }}',
        method: 'GET',
        data: {
            id: roleId,
            start_time: startTime,
            end_time: endTime,
            admin_id: adminId
        },
        beforeSend: function() {
            $('#department_project_table').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div><p>Đang tải dữ liệu...</p></div>');
        },
        success: function(response) {
            if (response.success) {
                renderDepartmentProjectReport(response.data);
            }
        }
    });
}

function loadDepartmentExpiredReport() {
    const roleId = $('#department_role').val();
    const startTime = $('#department_start_time').val();
    const adminId = $('#department_admin').val();

    if (!roleId) {
        init.showNoty('Vui lòng chọn phòng ban!', 'warning');
        return;
    }

    $.ajax({
        url: '{{ route("admin.role.report3") }}',
        method: 'GET',
        data: {
            id: roleId,
            start_time: startTime,
            admin_id: adminId
        },
        beforeSend: function() {
            $('#department_expired_table').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div><p>Đang tải dữ liệu...</p></div>');
        },
        success: function(response) {
            if (response.success) {
                renderDepartmentExpiredReport(response.data);
            }
        }
    });
}

function renderDepartmentProjectReport(data) {
    let html = '<div class="accordion accordion-toggle-arrow" id="projectAccordion">';

    data.forEach((member, index) => {
        html += `<div class="card">
            <div class="card-header">
                <div class="card-title" data-toggle="collapse" data-target="#project_collapse_${index}">
                    <div class="d-flex justify-content-between w-100">
                        <span><i class="flaticon2-user"></i> ${member.admin}</span>
                        <span class="mr-5">
                            <span class="label label-primary label-inline mr-2">Tổng: ${member.total}</span>
                            <span class="label label-success label-inline mr-2">Hoàn thành: ${member.total_complete}</span>
                            <span class="label label-info label-inline mr-2">Brand: ${member.total_branding}</span>
                            <span class="label label-warning label-inline">Marketing: ${member.total_mkt}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div id="project_collapse_${index}" class="collapse" data-parent="#projectAccordion">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Dự án</th>
                                    <th>Loại</th>
                                    <th>KL cần làm</th>
                                    <th>Đã hoàn thành</th>
                                    <th>Còn lại</th>
                                </tr>
                            </thead>
                            <tbody>`;

        member.projects.forEach(project => {
            const typeLabel = project.type == 1 ? 'Marketing' : (project.type == 2 ? 'Branding' : 'Video');
            const typeClass = project.type == 1 ? 'warning' : (project.type == 2 ? 'info' : 'danger');
            const remaining = project.qty - project.complete;

            html += `<tr>
                <td>${project.name}</td>
                <td><span class="label label-${typeClass} label-inline">${typeLabel}</span></td>
                <td><strong>${project.qty}</strong></td>
                <td><span class="label label-success label-inline">${project.complete}</span></td>
                <td><span class="label label-${remaining > 0 ? 'warning' : 'success'} label-inline">${remaining}</span></td>
            </tr>`;
        });

        html += `</tbody></table></div></div></div></div>`;
    });

    html += '</div>';
    $('#department_project_table').html(html);
}

function renderDepartmentExpiredReport(data) {
    let html = '<div class="accordion accordion-toggle-arrow" id="expiredAccordion">';

    data.forEach((member, index) => {
        html += `<div class="card">
            <div class="card-header">
                <div class="card-title" data-toggle="collapse" data-target="#expired_collapse_${index}">
                    <div class="d-flex justify-content-between w-100">
                        <span><i class="flaticon2-user"></i> ${member.admin}</span>
                        <span class="label label-danger label-inline mr-5">Dự án sắp hết hạn: ${member.total}</span>
                    </div>
                </div>
            </div>
            <div id="expired_collapse_${index}" class="collapse" data-parent="#expiredAccordion">
                <div class="card-body">`;

        if (member.projects.length > 0) {
            html += `<div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên dự án</th>
                                    <th>Ngày hết hạn</th>
                                </tr>
                            </thead>
                            <tbody>`;

            member.projects.forEach((project, pIndex) => {
                html += `<tr>
                    <td>${pIndex + 1}</td>
                    <td>${project.name}</td>
                    <td><span class="label label-danger label-inline">${project.expired_time}</span></td>
                </tr>`;
            });

            html += `</tbody></table></div>`;
        } else {
            html += '<p class="text-muted">Không có dự án sắp hết hạn</p>';
        }

        html += `</div></div></div>`;
    });

    html += '</div>';
    $('#department_expired_table').html(html);
}
</script>
