<!-- Báo cáo cá nhân -->
<div class="filter-card">
    <h5 class="mb-4"><i class="flaticon2-filter"></i> Bộ lọc</h5>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Từ ngày:</label>
                <input type="date" class="form-control" id="personal_start_time" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Đến ngày:</label>
                <input type="date" class="form-control" id="personal_end_time" value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Trạng thái:</label>
                <select class="form-control" id="personal_status">
                    <option value="">Tất cả</option>
                    <option value="0">Chưa làm</option>
                    <option value="expired">Trễ hạn</option>
                    <option value="1">Hoàn thành</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-primary btn-block" onclick="loadPersonalReport()">
                    <i class="flaticon2-reload"></i> Xem báo cáo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê tổng quan -->
<div class="row mb-5">
    <div class="col-md-3">
        <div class="card card-stats bg-light-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50 symbol-light-primary mr-4">
                        <span class="symbol-label">
                            <i class="flaticon2-layers-1 text-primary icon-2x"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Tổng task</h6>
                        <h2 class="stat-number text-primary" id="total_tasks">0</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-light-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50 symbol-light-success mr-4">
                        <span class="symbol-label">
                            <i class="flaticon2-check-mark text-success icon-2x"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Hoàn thành</h6>
                        <h2 class="stat-number text-success" id="completed_tasks">0</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-light-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50 symbol-light-warning mr-4">
                        <span class="symbol-label">
                            <i class="flaticon2-time text-warning icon-2x"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Chưa làm</h6>
                        <h2 class="stat-number text-warning" id="pending_tasks">0</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-light-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50 symbol-light-danger mr-4">
                        <span class="symbol-label">
                            <i class="flaticon2-exclamation text-danger icon-2x"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Trễ hạn</h6>
                        <h2 class="stat-number text-danger" id="expired_tasks">0</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bảng danh sách task -->
<div id="personal_report_table">
    <div class="text-center p-5">
        <p class="text-muted">Nhấn "Xem báo cáo" để tải dữ liệu</p>
    </div>
</div>
