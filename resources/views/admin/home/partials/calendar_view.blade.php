<!-- Lịch công việc -->
<div class="row mb-5">
    <div class="col-12">
        <h5 class="mb-4"><i class="flaticon2-calendar-8"></i> Lịch công việc - Deadline các task</h5>
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
<div class="card card-custom">
    <div class="card-body">
        <div id="calendar"></div>
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

<script>
let calendar_currentTicketId = null;
let calendar_currentTicketGroupId = null;
let calendar_currentTicketPhaseId = null;

$(document).ready(function() {
    // Event handler cho nút "Xem chi tiết"
    $('#btn_view_ticket_detail').on('click', function() {
        if (calendar_currentTicketId && calendar_currentTicketGroupId && calendar_currentTicketPhaseId) {
            // Mở trang ticket trong tab mới
            const url = '{{ route("admin.ticket.index", ["gid" => ":gid", "pid" => ":pid"]) }}'
                .replace(':gid', calendar_currentTicketGroupId)
                .replace(':pid', calendar_currentTicketPhaseId);

            window.open(url, '_blank');

            // Đóng modal hiện tại
            $('#modalTicketDetail').modal('hide');
        }
    });
});

// Cập nhật hàm showTicketDetail nếu có
if (typeof window.showTicketDetail_original === 'undefined' && typeof window.showTicketDetail !== 'undefined') {
    window.showTicketDetail_original = window.showTicketDetail;
    window.showTicketDetail = function(event) {
        const props = event.extendedProps;

        // Lưu thông tin ticket
        calendar_currentTicketId = props.ticket_id;
        calendar_currentTicketGroupId = props.group_id;
        calendar_currentTicketPhaseId = props.phase_id;

        // Gọi hàm gốc
        window.showTicketDetail_original(event);
    };
}
</script>
