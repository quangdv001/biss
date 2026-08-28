@extends('admin.layout.main')
@section('title')
Dashboard - Báo cáo
@endsection
@section('lib_css')
<link href="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<style>
    .nav-tabs .nav-link {
        font-weight: 600;
        font-size: 14px;
    }
    .card-stats {
        border-left: 4px solid #3699FF;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
    }
    .filter-card {
        background: #F3F6F9;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    /* Accordion arrow rotation */
    .card-header[data-toggle="collapse"] .ki-arrow-down {
        transition: transform 0.3s ease;
    }
    .card-header[data-toggle="collapse"]:not(.collapsed) .ki-arrow-down {
        transform: rotate(180deg);
    }
    .card-header[data-toggle="collapse"]:hover {
        background-color: #F3F6F9;
    }
</style>
@endsection
@section('lib_js')
<script src="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.js"></script>
@endsection
@section('content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6" id="kt_subheader">
    <div class="w-100 d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <h5 class="text-dark font-weight-bold my-1 mr-5">Dashboard - Báo cáo</h5>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <button type="button" id="btn_sync_expired_projects" class="btn btn-light-primary font-weight-bold">
                <i class="flaticon-refresh"></i> Đồng bộ
            </button>
        </div>
    </div>
</div>
<!--end::Subheader-->

<div class="content flex-column-fluid" id="kt_content">
    <div class="card card-custom">
        <div class="card-header card-header-tabs-line">
            <ul class="nav nav-tabs nav-bold nav-tabs-line" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab_personal" role="tab">
                        <span class="nav-icon"><i class="flaticon2-user"></i></span>
                        <span class="nav-text">Báo cáo cá nhân</span>
                    </a>
                </li>
                @if($isAdmin || $userRoles->count() > 0)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_department" role="tab">
                        <span class="nav-icon"><i class="flaticon2-group"></i></span>
                        <span class="nav-text">Báo cáo phòng ban</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_project" role="tab">
                        <span class="nav-icon"><i class="flaticon2-pie-chart-3"></i></span>
                        <span class="nav-text">Báo cáo dự án</span>
                    </a>
                </li>
                @endif
                @if($canViewAdsReport)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_ads" role="tab">
                        <span class="nav-icon"><i class="flaticon2-graph"></i></span>
                        <span class="nav-text">Báo cáo Ads</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                <!-- Tab Cá nhân -->
                <div class="tab-pane fade show active" id="tab_personal" role="tabpanel">
                    @include('admin.home.partials.personal_report')
                </div>

                <!-- Tab Phòng ban -->
                @if($isAdmin || $userRoles->count() > 0)
                <div class="tab-pane fade" id="tab_department" role="tabpanel">
                    @include('admin.home.partials.department_report')
                </div>

                <!-- Tab Dự án -->
                <div class="tab-pane fade" id="tab_project" role="tabpanel">
                    @include('admin.home.partials.project_report')
                </div>
                @endif

                <!-- Tab Ads -->
                @if($canViewAdsReport)
                <div class="tab-pane fade" id="tab_ads" role="tabpanel">
                    @include('admin.home.partials.ads_report')
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
var adsProjectIndexUrlTemplate = '{{ route("admin.ads.index", ["id" => "__ID__"]) }}';

$(document).ready(function() {
    // Initialize Select2 for personal admin filter
    $('#personal_admin_id').select2({
        placeholder: 'Chọn nhân sự phụ trách',
        allowClear: true,
        width: '100%'
    });

    // Initialize Select2 for project planer filter
    $('#project_admin_id').select2({
        placeholder: 'Chọn account planer',
        allowClear: true,
        width: '100%'
    });

    // Initialize Select2 for ads handler report filter
    $('#ads_handler_id').select2({
        placeholder: 'Chọn người xử lý',
        allowClear: true,
        width: '100%'
    });

    // Initialize Select2 for ads handler report project filter (search theo tên dự án)
    $('#ads_handler_project_id').select2({
        placeholder: '-- Tìm và chọn dự án --',
        allowClear: true,
        width: '100%'
    });

    // Initialize Select2 for ads account report filter (search theo tài khoản quảng cáo)
    $('#ads_account_filter').select2({
        placeholder: '-- Tìm và chọn tài khoản quảng cáo --',
        allowClear: true,
        width: '100%'
    });

    // Reload account report when ads account filter changes
    $('#ads_account_filter').on('change', function() {
        const account = $(this).val();
        if (!account) {
            $('#ads_account_report_result').hide();
            $('#ads_account_empty').show();
            return;
        }
        loadAdsAccountReport(account);
    });

    // Reload project report when admin filter changes
    $('#project_admin_id').on('change', function() {
        if (window.projectReportLoaded) {
            loadProjectReport();
        }
    });

    // Đồng bộ trạng thái dự án hết hạn
    $('#btn_sync_expired_projects').on('click', function() {
        var $btn = $(this);
        $.ajax({
            url: '{{ route("admin.home.syncExpiredProjects") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Đang đồng bộ...');
            },
            success: function(response) {
                if (response.success) {
                    init.showNoty(response.message, 'success');
                    if (response.count > 0) {
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                } else {
                    init.showNoty(response.message || 'Có lỗi xảy ra!', 'error');
                }
            },
            error: function() {
                init.showNoty('Không thể đồng bộ dữ liệu!', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="flaticon-refresh"></i> Đồng bộ');
            }
        });
    });

    // Load personal report on page load
    loadPersonalReport();

    // Load department report when tab is clicked
    $('a[href="#tab_department"]').on('shown.bs.tab', function() {
        if (!window.departmentReportLoaded) {
            window.departmentReportLoaded = true;
        }
    });

    // Load project report when tab is clicked
    $('a[href="#tab_project"]').on('shown.bs.tab', function() {
        if (!window.projectReportLoaded) {
            loadProjectReport();
            window.projectReportLoaded = true;
        }
    });

    // Load ads report when tab is clicked
    $('a[href="#tab_ads"]').on('shown.bs.tab', function() {
        if (!window.adsReportLoaded) {
            loadAdsBudgetAlertReport();
            loadAdsHandlerReport();
            loadAdsAccountList();
            window.adsReportLoaded = true;
        }
    });

    // Load admin list when role changes in department report
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

    // Load project report and expired report when switching tabs in department section
    $('a[href="#dept_project_report"]').on('shown.bs.tab', function() {
        const roleId = $('#department_role').val();
        if (roleId && window.departmentReportLoaded) {
            loadDepartmentProjectReport();
        }
    });

    $('a[href="#dept_expired_report"]').on('shown.bs.tab', function() {
        const roleId = $('#department_role').val();
        if (roleId && window.departmentReportLoaded) {
            loadDepartmentExpiredReport();
        }
    });
});

function loadPersonalReport() {
    const startTime = $('#personal_start_time').val();
    const endTime = $('#personal_end_time').val();
    const status = $('#personal_status').val();
    const planerId = $('#personal_admin_id').val();

    $.ajax({
        url: '{{ route("admin.home.getPersonalReport") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            start_time: startTime,
            end_time: endTime,
            status: status,
            planer_id: planerId
        },
        beforeSend: function() {
            $('#personal_report_table').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div><p>Đang tải dữ liệu...</p></div>');
        },
        success: function(response) {
            if (response.success) {
                renderPersonalReport(response.data);
                // Update stats
                $('#total_tasks').text(response.stats.total);
                $('#completed_tasks').text(response.stats.completed);
                $('#pending_tasks').text(response.stats.pending);
                $('#expired_tasks').text(response.stats.expired);
                $('#on_time_rate').text(response.stats.on_time_rate);
                $('#completed_on_time').text(response.stats.completed_on_time);
                $('#total_completed').text(response.stats.completed);
            } else {
                init.showNoty(response.message || 'Có lỗi xảy ra!', 'error');
            }
        },
        error: function() {
            init.showNoty('Không thể tải dữ liệu báo cáo!', 'error');
        }
    });
}

function loadDepartmentReport() {
    const roleId = $('#department_role').val();
    const startTime = $('#department_start_time').val();
    const endTime = $('#department_end_time').val();
    const adminId = $('#department_admin').val();

    if (!roleId) {
        init.showNoty('Vui lòng chọn phòng ban!', 'warning');
        return;
    }

    // Load tất cả 3 loại báo cáo cùng lúc
    loadDepartmentTaskReport();
    loadDepartmentProjectReport();
    loadDepartmentExpiredReport();
}

// Báo cáo theo Task (report)
function loadDepartmentTaskReport() {
    const roleId = $('#department_role').val();
    const startTime = $('#department_start_time').val();
    const endTime = $('#department_end_time').val();
    const adminId = $('#department_admin').val();

    $.ajax({
        url: '{{ route("admin.role.report") }}',
        method: 'GET',
        data: {
            id: roleId,
            start_time: startTime,
            end_time: endTime,
            admin_id: adminId
        },
        beforeSend: function() {
            $('#department_report_table').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div><p>Đang tải dữ liệu...</p></div>');
        },
        success: function(response) {
            if (response.success) {
                renderDepartmentReport(response.data);
            } else {
                init.showNoty(response.message || 'Có lỗi xảy ra!', 'error');
            }
        },
        error: function() {
            init.showNoty('Không thể tải dữ liệu báo cáo!', 'error');
        }
    });
}

// Báo cáo theo Dự án (report2)
function loadDepartmentProjectReport() {
    const roleId = $('#department_role').val();
    const startTime = $('#department_start_time').val();
    const endTime = $('#department_end_time').val();
    const adminId = $('#department_admin').val();

    if (!roleId) return;

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
            } else {
                init.showNoty(response.message || 'Có lỗi xảy ra!', 'error');
            }
        },
        error: function() {
            init.showNoty('Không thể tải dữ liệu báo cáo dự án!', 'error');
        }
    });
}

// Dự án sắp hết hạn (report3)
function loadDepartmentExpiredReport() {
    const roleId = $('#department_role').val();
    const startTime = $('#department_start_time').val();
    const adminId = $('#department_admin').val();

    if (!roleId) return;

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
            } else {
                init.showNoty(response.message || 'Có lỗi xảy ra!', 'error');
            }
        },
        error: function() {
            init.showNoty('Không thể tải dữ liệu báo cáo dự án sắp hết hạn!', 'error');
        }
    });
}

function renderPersonalReport(data) {
    let html = '<div class="table-responsive"><table class="table table-bordered table-hover" id="personal_table"><thead><tr>';
    html += '<th>STT</th><th>Dự án</th><th>Tên task</th><th>Mô tả</th><th>Deadline</th>';
    html += '<th>Hoàn thành</th><th>Trạng thái</th><th>Account Planer</th></tr></thead><tbody>';

    data.forEach((item, index) => {
        const deadlineDate = new Date(item.deadline_time * 1000);
        const completeDate = item.complete_time ? new Date(item.complete_time * 1000) : null;
        const statusClass = item.status == 1 ? 'success' : (item.deadline_time < Date.now()/1000 ? 'danger' : 'warning');
        const statusText = item.status == 1 ? 'Hoàn thành' : (item.deadline_time < Date.now()/1000 ? 'Trễ hạn' : 'Chưa làm');

        html += `<tr>
            <td>${index + 1}</td>
            <td>${item.project_name || ''}</td>
            <td>${item.name}</td>
            <td>${item.description || ''}</td>
            <td>${deadlineDate.toLocaleDateString('vi-VN')}</td>
            <td>${completeDate ? completeDate.toLocaleDateString('vi-VN') : '-'}</td>
            <td><span class="label label-${statusClass} label-inline">${statusText}</span></td>
            <td>${item.planer || 'Chưa có planer'}</td>
        </tr>`;
    });

    html += '</tbody></table></div>';
    $('#personal_report_table').html(html);

    // Initialize DataTable
    $('#personal_table').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[4, 'asc']]
    });
}

function renderDepartmentReport(data) {
    let html = '<div class="accordion accordion-toggle-arrow" id="departmentAccordion">';

    data.forEach((member, index) => {
        html += `<div class="card">
            <div class="card-header">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse_${index}">
                    <i class="flaticon2-user"></i> ${member.admin}
                </div>
            </div>
            <div id="collapse_${index}" class="collapse" data-parent="#departmentAccordion">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Dự án</th>
                                    <th>Tổng task</th>
                                    <th>Mới</th>
                                    <th>Trễ hạn</th>
                                    <th>Hoàn thành</th>
                                    <th>Đúng hạn</th>
                                    <th>Trễ hạn</th>
                                    <th>% Đúng hạn</th>
                                </tr>
                            </thead>
                            <tbody>`;

        member.projects.forEach(project => {
            html += `<tr>
                <td>${project.project}</td>
                <td>${project.report.total}</td>
                <td><span class="label label-warning">${project.report.new}</span></td>
                <td><span class="label label-danger">${project.report.expired}</span></td>
                <td><span class="label label-success">${project.report.done}</span></td>
                <td>${project.report.done_on_time}</td>
                <td>${project.report.done_out_time}</td>
                <td><strong>${project.report.percent_on_time}%</strong></td>
            </tr>`;
        });

        html += `</tbody></table></div></div></div></div>`;
    });

    html += '</div>';
    $('#department_report_table').html(html);
}

function loadProjectReport() {
    const adminId = $('#project_admin_id').val();

    $.ajax({
        url: '{{ route("admin.home.getProjectReport") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            admin_id: adminId
        },
        beforeSend: function() {
            $('#active_projects_content, #late_projects_content, #pending_projects_content, #expiring_projects_content').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div><p>Đang tải dữ liệu...</p></div>');
        },
        success: function(response) {
            if (response.success) {
                renderProjectReport(response.data);
            } else {
                init.showNoty(response.message || 'Có lỗi xảy ra!', 'error');
            }
        },
        error: function() {
            init.showNoty('Không thể tải dữ liệu báo cáo dự án!', 'error');
        }
    });
}

function renderProjectReport(data) {
    // 1. Dự án đang hoạt động
    renderProjectList(data.active, 'active_projects', 'Không có dự án nào đang hoạt động');
    $('#active_projects_count').text(data.active.length);
    $('#active_projects_badge').text(data.active.length);

    // 2. Dự án trễ (đã hoàn thành nhưng còn task)
    renderLateProjects(data.late);
    $('#late_projects_count').text(data.late.length);
    $('#late_projects_badge').text(data.late.length);

    // 3. Dự án có task chưa hoàn thành
    renderProjectList(data.pending, 'pending_projects', 'Không có dự án nào có task chưa hoàn thành');
    $('#pending_projects_count').text(data.pending.length);
    $('#pending_projects_badge').text(data.pending.length);

    // 4. Dự án sắp hết hạn
    renderExpiringProjects(data.expiring);
    $('#expiring_projects_count').text(data.expiring.length);
    $('#expiring_projects_badge').text(data.expiring.length);
}

function renderProjectList(projects, containerId, emptyMessage) {
    let html = '';

    if (projects.length === 0) {
        html = `<div class="text-center p-5"><p class="text-muted">${emptyMessage}</p></div>`;
    } else {
        html = '<div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr>';
        html += '<th>STT</th><th>Tên dự án</th><th>Tổng task</th><th>Hoàn thành</th><th>Chưa làm</th><th>Account Planer</th>';
        html += '</tr></thead><tbody>';

        projects.forEach((project, index) => {
            html += `<tr>
                <td>${index + 1}</td>
                <td><strong>${project.name}</strong></td>
                <td>${project.total_tasks}</td>
                <td><span class="label label-success">${project.completed_tasks}</span></td>
                <td><span class="label label-warning">${project.pending_tasks}</span></td>
                <td>${project.admins}</td>
            </tr>`;
        });

        html += '</tbody></table></div>';
    }

    $(`#${containerId}_content`).html(html);
}

function renderLateProjects(projects) {
    let html = '';

    if (projects.length === 0) {
        html = '<div class="text-center p-5"><p class="text-muted">Không có dự án nào bị trễ</p></div>';
    } else {
        html = '<div class="accordion accordion-toggle-arrow" id="lateProjectsAccordion">';

        projects.forEach((project, index) => {
            html += `<div class="card">
                <div class="card-header">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#late_${index}">
                        <i class="flaticon2-exclamation text-warning"></i>
                        <strong>${project.name}</strong>
                        <span class="badge badge-warning ml-2">${project.pending_tasks} task chưa làm</span>
                        <span class="text-muted ml-2">| Account Planer: ${project.admins}</span>
                    </div>
                </div>
                <div id="late_${index}" class="collapse" data-parent="#lateProjectsAccordion">
                    <div class="card-body">
                        <h6>Nhóm có task chưa hoàn thành:</h6>
                        <ul class="list-group">`;

            if (project.pending_groups && project.pending_groups.length > 0) {
                project.pending_groups.forEach(group => {
                    html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        ${group.group_name}
                        <span class="badge badge-danger badge-pill">${group.pending_count} task</span>
                    </li>`;
                });
            }

            html += `</ul></div></div></div>`;
        });

        html += '</div>';
    }

    $('#late_projects_content').html(html);
}

function renderExpiringProjects(projects) {
    let html = '';

    if (projects.length === 0) {
        html = '<div class="text-center p-5"><p class="text-muted">Không có dự án nào sắp hết hạn</p></div>';
    } else {
        html = '<div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr>';
        html += '<th>STT</th><th>Tên dự án</th><th>Ngày hết hạn</th><th>Còn lại</th><th>Tổng task</th><th>Hoàn thành</th><th>Chưa làm</th><th>Account Planer</th>';
        html += '</tr></thead><tbody>';

        projects.forEach((project, index) => {
            const expiredDate = new Date(project.expired_time * 1000);
            html += `<tr>
                <td>${index + 1}</td>
                <td><strong>${project.name}</strong></td>
                <td>${expiredDate.toLocaleDateString('vi-VN')}</td>
                <td><span class="badge badge-danger">${project.days_left} ngày</span></td>
                <td>${project.total_tasks}</td>
                <td><span class="label label-success">${project.completed_tasks}</span></td>
                <td><span class="label label-warning">${project.pending_tasks}</span></td>
                <td>${project.admins}</td>
            </tr>`;
        });

        html += '</tbody></table></div>';
    }

    $('#expiring_projects_content').html(html);
}

// Render báo cáo dự án theo KL (report2)
function renderDepartmentProjectReport(data) {
    if (!data || data.length === 0) {
        $('#department_project_table').html('<div class="text-center p-5"><p class="text-muted">Không có dữ liệu báo cáo</p></div>');
        return;
    }

    let html = '<div class="accordion accordion-toggle-arrow" id="projectAccordion">';

    data.forEach((member, index) => {
        html += `<div class="card">
            <div class="card-header">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#project_collapse_${index}">
                    <div class="d-flex justify-content-between w-100 align-items-center">
                        <span><i class="flaticon2-user"></i> <strong>${member.admin}</strong></span>
                        <span class="mr-5">
                            <span class="label label-primary label-inline mr-2">Tổng: ${member.total}</span>
                            <span class="label label-success label-inline mr-2">Hoàn thành: ${member.total_complete}</span>
                            <span class="label label-info label-inline mr-2">Branding: ${member.total_branding}</span>
                            <span class="label label-warning label-inline mr-2">Marketing: ${member.total_mkt}</span>
                            <span class="label label-danger label-inline">Video: ${member.total_video}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div id="project_collapse_${index}" class="collapse" data-parent="#projectAccordion">
                <div class="card-body">`;

        if (member.projects && member.projects.length > 0) {
            html += `<div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Dự án</th>
                                    <th>Loại</th>
                                    <th>KL cần làm</th>
                                    <th>Đã hoàn thành</th>
                                    <th>Còn lại</th>
                                    <th>Tiến độ</th>
                                </tr>
                            </thead>
                            <tbody>`;

            member.projects.forEach((project, pIndex) => {
                const typeLabel = project.type == 1 ? 'Marketing' : (project.type == 2 ? 'Branding' : (project.type == 3 ? 'Video' : 'Khác'));
                const typeClass = project.type == 1 ? 'warning' : (project.type == 2 ? 'info' : (project.type == 3 ? 'danger' : 'secondary'));
                const remaining = project.qty - project.complete;
                const progress = project.qty > 0 ? Math.round((project.complete / project.qty) * 100) : 0;
                const progressClass = progress >= 80 ? 'success' : (progress >= 50 ? 'warning' : 'danger');

                html += `<tr>
                    <td>${pIndex + 1}</td>
                    <td><strong>${project.name}</strong></td>
                    <td><span class="label label-${typeClass} label-inline">${typeLabel}</span></td>
                    <td><span class="label label-primary label-inline">${project.qty}</span></td>
                    <td><span class="label label-success label-inline">${project.complete}</span></td>
                    <td><span class="label label-${remaining > 0 ? 'warning' : 'success'} label-inline">${remaining}</span></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress" style="width: 100px; height: 20px;">
                                <div class="progress-bar bg-${progressClass}" role="progressbar" style="width: ${progress}%"
                                     aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100">
                                    ${progress}%
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>`;
            });

            html += `</tbody></table></div>`;
        } else {
            html += '<p class="text-muted">Không có dự án nào</p>';
        }

        html += `</div></div></div>`;
    });

    html += '</div>';
    $('#department_project_table').html(html);
}

// Render dự án sắp hết hạn (report3)
function renderDepartmentExpiredReport(data) {
    if (!data || data.length === 0) {
        $('#department_expired_table').html('<div class="text-center p-5"><p class="text-muted">Không có dữ liệu báo cáo</p></div>');
        return;
    }

    let html = '<div class="accordion accordion-toggle-arrow" id="expiredAccordion">';

    data.forEach((member, index) => {
        html += `<div class="card">
            <div class="card-header">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#expired_collapse_${index}">
                    <div class="d-flex justify-content-between w-100 align-items-center">
                        <span><i class="flaticon2-user"></i> <strong>${member.admin}</strong></span>
                        <span class="label label-danger label-inline mr-5">
                            <i class="flaticon2-exclamation"></i> ${member.total} dự án sắp hết hạn
                        </span>
                    </div>
                </div>
            </div>
            <div id="expired_collapse_${index}" class="collapse" data-parent="#expiredAccordion">
                <div class="card-body">`;

        if (member.projects && member.projects.length > 0) {
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
                    <td><strong>${project.name}</strong></td>
                    <td><span class="label label-danger label-inline"><i class="flaticon2-calendar-9"></i> ${project.expired_time}</span></td>
                </tr>`;
            });

            html += `</tbody></table></div>`;
        } else {
            html += '<div class="alert alert-success"><i class="flaticon2-check-mark"></i> Không có dự án sắp hết hạn</div>';
        }

        html += `</div></div></div>`;
    });

    html += '</div>';
    $('#department_expired_table').html(html);
}

function loadAdsBudgetAlertReport() {
    const month = $('#ads_alert_month').val();

    $.ajax({
        url: '{{ route("admin.ads.budgetAlertReport") }}',
        method: 'GET',
        data: { month: month },
        beforeSend: function() {
            $('#ads_total_budget_content, #ads_total_spend_content, #ads_near_limit_content, #ads_over_budget_content')
                .html('<div class="text-center p-5"><div class="spinner-border" role="status"></div></div>');
        },
        success: function(response) {
            if (response.success) {
                renderAdsBudgetAlertReport(response.data);
            } else {
                init.showNoty(response.message || 'Có lỗi xảy ra!', 'error');
            }
        },
        error: function() {
            init.showNoty('Không thể tải dữ liệu cảnh báo ngân sách!', 'error');
        }
    });
}

function renderAdsBudgetAlertGroup(containerId, accordionId, projects, emptyMessage) {
    if (!projects || !projects.length) {
        $('#' + containerId).html(`<div class="alert alert-light-primary">${emptyMessage}</div>`);
        return;
    }

    let html = `<div class="accordion accordion-toggle-arrow" id="${accordionId}">`;

    projects.forEach((project, index) => {
        const collapseId = accordionId + '_collapse_' + index;
        const projectName = project.project_id
            ? `<a href="${adsProjectIndexUrlTemplate.replace('__ID__', project.project_id)}">${project.project}</a>`
            : project.project;

        html += `<div class="card">
            <div class="card-header">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#${collapseId}">
                    <div class="d-flex justify-content-between w-100 align-items-center">
                        <span><i class="flaticon2-pie-chart-3"></i> ${projectName}</span>
                        <span class="label label-warning label-inline mr-5">${project.count} chiến dịch</span>
                    </div>
                </div>
            </div>
            <div id="${collapseId}" class="collapse" data-parent="#${accordionId}">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Chiến dịch</th>
                                    <th>Ngân sách nhận</th>
                                    <th>Đã chi</th>
                                    <th>Còn dư</th>
                                </tr>
                            </thead>
                            <tbody>`;

        project.campaigns.forEach(c => {
            const campaignName = (project.project_id && c.campaign_id)
                ? `<a href="${adsProjectIndexUrlTemplate.replace('__ID__', project.project_id)}?campaign=${c.campaign_id}">${c.campaign}</a>`
                : c.campaign;
            html += `<tr>
                <td>${campaignName}</td>
                <td>${Number(c.total_budget).toLocaleString('vi-VN')}</td>
                <td>${Number(c.total_spend).toLocaleString('vi-VN')}</td>
                <td>${Number(c.remaining).toLocaleString('vi-VN')}</td>
            </tr>`;
        });

        html += `</tbody></table></div></div></div></div>`;
    });

    html += '</div>';
    $('#' + containerId).html(html);
}

function renderAdsBudgetAlertReport(data) {
    $('#ads_alert_total_budget, #ads_alert_total_budget_stat').text(Number(data.total_budget).toLocaleString('vi-VN'));
    $('#ads_alert_total_spend, #ads_alert_total_spend_stat').text(Number(data.total_spend).toLocaleString('vi-VN'));
    $('#ads_alert_near_limit_count, #ads_alert_near_limit_count_stat').text(data.near_limit_count);
    $('#ads_alert_over_budget_count, #ads_alert_over_budget_count_stat').text(data.over_budget_count);

    renderAdsBudgetAlertGroup('ads_near_limit_content', 'adsNearLimitAccordion', data.near_limit, 'Không có chiến dịch nào sắp hết ngân sách trong tháng này');
    renderAdsBudgetAlertGroup('ads_over_budget_content', 'adsOverBudgetAccordion', data.over_budget, 'Không có chiến dịch nào vượt ngân sách trong tháng này');
    renderAdsBudgetAlertGroup('ads_total_budget_content', 'adsTotalBudgetAccordion', data.budget_detail, 'Không có ngân sách nào được nhập trong tháng này');
    renderAdsBudgetAlertGroup('ads_total_spend_content', 'adsTotalSpendAccordion', data.spend_detail, 'Không có chi tiêu nào trong tháng này');
}

function loadAdsHandlerReport() {
    const handlerId = $('#ads_handler_id').val();
    const projectId = $('#ads_handler_project_id').val();
    const startTime = $('#ads_start_time').val();
    const endTime = $('#ads_end_time').val();

    $.ajax({
        url: '{{ route("admin.ads.handlerReport") }}',
        method: 'GET',
        data: {
            handler_id: handlerId,
            project_id: projectId,
            start_time: startTime,
            end_time: endTime
        },
        beforeSend: function() {
            $('#ads_handler_report_table').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div><p>Đang tải dữ liệu...</p></div>');
        },
        success: function(response) {
            if (response.success) {
                renderAdsHandlerReport(response.data);
            } else {
                init.showNoty(response.message || 'Có lỗi xảy ra!', 'error');
            }
        },
        error: function() {
            init.showNoty('Không thể tải dữ liệu báo cáo theo người xử lý!', 'error');
        }
    });
}

function renderAdsHandlerReport(data) {
    if (!data || data.length === 0) {
        $('#ads_handler_report_table').html('<div class="text-center p-5"><p class="text-muted">Không có dữ liệu báo cáo</p></div>');
        return;
    }

    let html = '<div class="accordion accordion-toggle-arrow" id="adsHandlerAccordion">';

    data.forEach((handler, index) => {
        html += `<div class="card">
            <div class="card-header">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#ads_handler_collapse_${index}">
                    <i class="flaticon2-user"></i> ${handler.handler}
                </div>
            </div>
            <div id="ads_handler_collapse_${index}" class="collapse" data-parent="#adsHandlerAccordion">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Dự án / Chiến dịch</th>
                                    <th>Tổng ngân sách</th>
                                    <th>Tổng đã chi</th>
                                    <th>Còn dư</th>
                                    <th>Kết quả</th>
                                </tr>
                            </thead>
                            <tbody>`;

        handler.projects.forEach(project => {
            const projectName = project.project_id
                ? `<a href="${adsProjectIndexUrlTemplate.replace('__ID__', project.project_id)}">${project.project}</a>`
                : project.project;
            html += `<tr class="font-weight-bold bg-light">
                <td>${projectName}</td>
                <td>${Number(project.total_budget).toLocaleString('vi-VN')}</td>
                <td>${Number(project.total_spend).toLocaleString('vi-VN')}</td>
                <td>${Number(project.remaining).toLocaleString('vi-VN')}</td>
                <td>${Number(project.total_results).toLocaleString('vi-VN')}</td>
            </tr>`;
            (project.campaigns || []).forEach(c => {
                const campaignName = (project.project_id && c.campaign_id)
                    ? `<a href="${adsProjectIndexUrlTemplate.replace('__ID__', project.project_id)}?campaign=${c.campaign_id}">${c.campaign}</a>`
                    : c.campaign;
                html += `<tr>
                    <td class="pl-8">${campaignName}</td>
                    <td>${Number(c.total_budget).toLocaleString('vi-VN')}</td>
                    <td>${Number(c.total_spend).toLocaleString('vi-VN')}</td>
                    <td>${Number(c.remaining).toLocaleString('vi-VN')}</td>
                    <td>${Number(c.total_results).toLocaleString('vi-VN')}</td>
                </tr>`;
            });
        });

        html += `</tbody></table></div></div></div></div>`;
    });

    html += '</div>';
    $('#ads_handler_report_table').html(html);
}

function escapeHtml(str) {
    return String(str === null || str === undefined ? '' : str).replace(/[&<>"']/g, function (s) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[s];
    });
}

function loadAdsAccountList() {
    $.get('{{ route("admin.ads.accountList") }}', function(response) {
        if (response.success) {
            let options = '<option value="">-- Tìm và chọn tài khoản quảng cáo --</option>';
            response.data.forEach(function(acc) {
                options += `<option value="${escapeHtml(acc)}">${escapeHtml(acc)}</option>`;
            });
            $('#ads_account_filter').html(options);
        }
    });
}

function loadAdsAccountReport(account) {
    $.ajax({
        url: '{{ route("admin.ads.accountReport") }}',
        method: 'GET',
        data: { account: account },
        beforeSend: function() {
            $('#ads_account_empty').hide();
            $('#ads_account_report_result').show();
            $('#ads_account_cards_table').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div></div>');
        },
        success: function(response) {
            if (response.success) {
                renderAdsAccountReport(response.data);
            } else {
                init.showNoty(response.message || 'Có lỗi xảy ra!', 'error');
            }
        },
        error: function() {
            init.showNoty('Không thể tải báo cáo theo tài khoản quảng cáo!', 'error');
        }
    });
}

function renderAdsAccountReport(data) {
    $('#ads_account_total_cards').text(data.total_cards);
    $('#ads_account_total_projects').text(data.total_projects);

    if (!data.cards || !data.cards.length) {
        $('#ads_account_cards_table').html('<div class="alert alert-light-primary">Không có dữ liệu</div>');
        return;
    }

    let html = '<div class="accordion accordion-toggle-arrow" id="adsAccountCardsAccordion">';

    data.cards.forEach((cardItem, index) => {
        const collapseId = 'ads_account_card_collapse_' + index;
        html += `<div class="card">
            <div class="card-header">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#${collapseId}">
                    <div class="d-flex justify-content-between w-100 align-items-center">
                        <span><i class="flaticon2-list"></i> Thẻ: <strong>${escapeHtml(cardItem.card)}</strong></span>
                        <span class="label label-primary label-inline mr-5">${cardItem.total_projects} dự án</span>
                    </div>
                </div>
            </div>
            <div id="${collapseId}" class="collapse" data-parent="#adsAccountCardsAccordion">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead><tr><th>Dự án</th><th>Chiến dịch</th></tr></thead>
                            <tbody>`;

        cardItem.projects.forEach(project => {
            const projectName = project.project_id
                ? `<a href="${adsProjectIndexUrlTemplate.replace('__ID__', project.project_id)}">${escapeHtml(project.project)}</a>`
                : escapeHtml(project.project);
            const campaignLinks = project.campaigns.map(c => {
                return (project.project_id && c.campaign_id)
                    ? `<a href="${adsProjectIndexUrlTemplate.replace('__ID__', project.project_id)}?campaign=${c.campaign_id}">${escapeHtml(c.campaign)}</a>`
                    : escapeHtml(c.campaign);
            }).join(', ');
            html += `<tr><td>${projectName}</td><td>${campaignLinks}</td></tr>`;
        });

        html += `</tbody></table></div></div></div></div>`;
    });

    html += '</div>';
    $('#ads_account_cards_table').html(html);
}

// Function để xem lịch công việc cá nhân
function viewPersonalCalendar() {
    const adminId = $('#personal_admin_id').val();
    let url = '{{ route("admin.home.personalCalendar") }}';

    if (adminId) {
        url = '{{ route("admin.home.personalCalendar", ":adminId") }}'.replace(':adminId', adminId);
    }

    // Mở trong tab mới
    window.open(url, '_blank');
}
</script>
@endsection
