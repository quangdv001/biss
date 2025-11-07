@extends('admin.layout.main')
@section('title')
Lịch công việc - {{ $selectedAdmin->username }}
@endsection
@section('lib_css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<style>
    .filter-card {
        background: #F3F6F9;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .admin-info-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }
</style>
@endsection
@section('lib_js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/vi.js"></script>
@endsection
@section('content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6" id="kt_subheader">
    <div class="w-100 d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <h5 class="text-dark font-weight-bold my-1 mr-5">
                    <i class="flaticon2-calendar-8 text-primary"></i> Lịch công việc cá nhân
                </h5>
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.home.index') }}" class="text-muted">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.home.dashboard') }}" class="text-muted">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-muted">Lịch công việc</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.home.dashboard') }}" class="btn btn-light-primary btn-sm">
                <i class="flaticon2-back"></i> Quay lại Dashboard
            </a>
        </div>
    </div>
</div>
<!--end::Subheader-->

<div class="content flex-column-fluid" id="kt_content">
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-calendar-8 text-primary"></i>
                    Lịch deadline các task
                </h3>
            </div>
        </div>
        <div class="card-body">
            <!-- Thông tin nhân sự -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="admin-info-badge text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="symbol symbol-40 symbol-light-white mr-3">
                                <span class="symbol-label">
                                    <i class="flaticon2-user icon-lg text-white"></i>
                                </span>
                            </div>
                            <div>
                                <h4 class="mb-0 text-white">{{ $selectedAdmin->username }}</h4>
                                <span class="text-white-50">
                                    @if($selectedAdmin->id == $user->id)
                                        Lịch công việc của tôi
                                    @else
                                        Lịch công việc cá nhân
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc -->
            <div class="filter-card">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn dự án:</label>
                            <select class="form-control" id="calendar_project_filter">
                                <option value="">Tất cả dự án</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 text-right">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-light-success" id="btn_today">
                                    <i class="flaticon2-calendar-2"></i> Hôm nay
                                </button>
                                <button type="button" class="btn btn-light-primary ml-2" id="btn_reload_calendar">
                                    <i class="flaticon2-reload"></i> Làm mới
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chú thích màu sắc -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center">
                        <span class="mr-4"><strong>Chú thích:</strong></span>
                        <span class="mr-4">
                            <span class="badge badge-success mr-2">■</span> Đã hoàn thành
                        </span>
                        <span class="mr-4">
                            <span class="badge badge-warning mr-2">■</span> Chưa làm
                        </span>
                        <span class="mr-4">
                            <span class="badge badge-danger mr-2">■</span> Trễ hạn
                        </span>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="row">
                <div class="col-12">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chi tiết ticket -->
<div class="modal fade" id="modalTicketDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="flaticon2-information"></i> Chi tiết công việc
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4 id="ticket_title" class="mb-3"></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Dự án:</label>
                            <p id="ticket_project" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Nhóm:</label>
                            <p id="ticket_group" class="mb-2"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Deadline:</label>
                            <p id="ticket_deadline" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Hoàn thành:</label>
                            <p id="ticket_complete" class="mb-2"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Trạng thái:</label>
                            <p id="ticket_status" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Người phụ trách:</label>
                            <p id="ticket_assignees" class="mb-2"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Mô tả:</label>
                            <p id="ticket_description" class="mb-2"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Ghi chú:</label>
                            <p id="ticket_note" class="mb-2"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_view_ticket_detail">
                    <i class="flaticon2-analytics-1"></i> Xem chi tiết
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
let calendar;
let currentTicketId = null;
let currentTicketGroupId = null;
let currentTicketPhaseId = null;

$(document).ready(function() {
    initCalendar();

    // Event handler cho nút "Xem chi tiết"
    $('#btn_view_ticket_detail').on('click', function() {
        if (currentTicketId && currentTicketGroupId && currentTicketPhaseId) {
            // Mở trang ticket trong tab mới
            const url = '{{ route("admin.ticket.index", ["gid" => ":gid", "pid" => ":pid"]) }}'
                .replace(':gid', currentTicketGroupId)
                .replace(':pid', currentTicketPhaseId) + '?id=' + currentTicketId;

            window.open(url, '_blank');

            // Đóng modal hiện tại
            $('#modalTicketDetail').modal('hide');
        }
    });
});

function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    const selectedAdminId = {{ $selectedAdmin->id }};

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'vi',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        buttonText: {
            today: 'Hôm nay',
            month: 'Tháng',
            week: 'Tuần',
            day: 'Ngày'
        },
        height: 'auto',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        },
        events: function(info, successCallback, failureCallback) {
            loadCalendarEvents(info.start, info.end, successCallback, failureCallback);
        },
        eventClick: function(info) {
            showTicketDetail(info.event);
        },
        eventDidMount: function(info) {
            // Tooltip
            $(info.el).tooltip({
                title: info.event.title,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        }
    });

    calendar.render();

    // Event listeners
    $('#calendar_project_filter').on('change', function() {
        calendar.refetchEvents();
    });

    $('#btn_today').on('click', function() {
        calendar.today();
    });

    $('#btn_reload_calendar').on('click', function() {
        calendar.refetchEvents();
    });
}

function loadCalendarEvents(start, end, successCallback, failureCallback) {
    const projectId = $('#calendar_project_filter').val();
    const selectedAdminId = {{ $selectedAdmin->id }};

    $.ajax({
        url: '{{ route("admin.home.getPersonalCalendarData") }}',
        method: 'GET',
        data: {
            admin_id: selectedAdminId,
            project_id: projectId,
            start: start.toISOString().split('T')[0],
            end: end.toISOString().split('T')[0]
        },
        success: function(events) {
            successCallback(events);
        },
        error: function() {
            failureCallback();
        }
    });
}

function showTicketDetail(event) {
    const props = event.extendedProps;

    // Lưu thông tin ticket để sử dụng khi bấm nút "Xem chi tiết"
    currentTicketId = props.ticket_id;
    currentTicketGroupId = props.group_id;
    currentTicketPhaseId = props.phase_id;

    // Set ticket info
    $('#ticket_title').text(event.title);
    $('#ticket_project').text(props.project_name || 'N/A');
    $('#ticket_group').text(props.group_name || 'N/A');

    // Format dates
    const deadlineDate = new Date(props.deadline_time * 1000);
    $('#ticket_deadline').html(`
        <span class="badge badge-light-primary">${deadlineDate.toLocaleDateString('vi-VN')} ${deadlineDate.toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}</span>
    `);

    if (props.complete_time) {
        const completeDate = new Date(props.complete_time * 1000);
        $('#ticket_complete').html(`
            <span class="badge badge-light-success">${completeDate.toLocaleDateString('vi-VN')} ${completeDate.toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}</span>
        `);
    } else {
        $('#ticket_complete').html('<span class="text-muted">Chưa hoàn thành</span>');
    }

    // Status
    let statusBadge = '';
    if (props.status == 1) {
        statusBadge = '<span class="badge badge-success">Đã hoàn thành</span>';
    } else if (props.deadline_time < Date.now()/1000) {
        statusBadge = '<span class="badge badge-danger">Trễ hạn</span>';
    } else {
        statusBadge = '<span class="badge badge-warning">Chưa làm</span>';
    }
    $('#ticket_status').html(statusBadge);

    // Assignees
    $('#ticket_assignees').text(props.assignees || 'Chưa phân công');

    // Description and note
    $('#ticket_description').text(props.description || 'Không có mô tả');
    $('#ticket_note').text(props.note || 'Không có ghi chú');

    // Show modal
    $('#modalTicketDetail').modal('show');
}
</script>
@endsection
