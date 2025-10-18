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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
$(document).ready(function() {
    // Load personal report on page load
    loadPersonalReport();

    // Load department report when tab is clicked
    $('a[href="#tab_department"]').on('shown.bs.tab', function() {
        if (!window.departmentReportLoaded) {
            loadDepartmentReport();
            window.departmentReportLoaded = true;
        }
    });
});

function loadPersonalReport() {
    const startTime = $('#personal_start_time').val();
    const endTime = $('#personal_end_time').val();
    const status = $('#personal_status').val();

    $.ajax({
        url: '{{ route("admin.home.getPersonalReport") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            start_time: startTime,
            end_time: endTime,
            status: status
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

function renderPersonalReport(data) {
    let html = '<div class="table-responsive"><table class="table table-bordered table-hover" id="personal_table"><thead><tr>';
    html += '<th>STT</th><th>Dự án</th><th>Tên task</th><th>Mô tả</th><th>Deadline</th>';
    html += '<th>Hoàn thành</th><th>Trạng thái</th><th>Độ ưu tiên</th></tr></thead><tbody>';

    data.forEach((item, index) => {
        const deadlineDate = new Date(item.deadline_time * 1000);
        const completeDate = item.complete_time ? new Date(item.complete_time * 1000) : null;
        const statusClass = item.status == 1 ? 'success' : (item.deadline_time < Date.now()/1000 ? 'danger' : 'warning');
        const statusText = item.status == 1 ? 'Hoàn thành' : (item.deadline_time < Date.now()/1000 ? 'Trễ hạn' : 'Chưa làm');
        const priorityText = item.priority == 1 ? 'Cao' : (item.priority == 2 ? 'Trung bình' : 'Thấp');

        html += `<tr>
            <td>${index + 1}</td>
            <td>${item.project_name || ''}</td>
            <td>${item.name}</td>
            <td>${item.description || ''}</td>
            <td>${deadlineDate.toLocaleDateString('vi-VN')}</td>
            <td>${completeDate ? completeDate.toLocaleDateString('vi-VN') : '-'}</td>
            <td><span class="label label-${statusClass} label-inline">${statusText}</span></td>
            <td>${priorityText}</td>
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
</script>
@endsection
