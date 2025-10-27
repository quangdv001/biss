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
    <div class="col-md-2">
        <div class="card card-stats bg-light-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-primary mr-3">
                        <span class="symbol-label">
                            <i class="flaticon2-layers-1 text-primary icon-xl"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 font-size-sm">Tổng task</h6>
                        <h3 class="stat-number text-primary mb-0" id="total_tasks">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-stats bg-light-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-success mr-3">
                        <span class="symbol-label">
                            <i class="flaticon2-check-mark text-success icon-xl"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 font-size-sm">Hoàn thành</h6>
                        <h3 class="stat-number text-success mb-0" id="completed_tasks">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-stats bg-light-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-warning mr-3">
                        <span class="symbol-label">
                            <i class="flaticon2-time text-warning icon-xl"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 font-size-sm">Chưa làm</h6>
                        <h3 class="stat-number text-warning mb-0" id="pending_tasks">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-stats bg-light-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-danger mr-3">
                        <span class="symbol-label">
                            <i class="flaticon2-exclamation text-danger icon-xl"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 font-size-sm">Trễ hạn</h6>
                        <h3 class="stat-number text-danger mb-0" id="expired_tasks">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stats bg-light-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-info mr-3">
                        <span class="symbol-label">
                            <i class="flaticon2-chart2 text-info icon-xl"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 font-size-sm">Tỉ lệ hoàn thành đúng hạn</h6>
                        <h3 class="stat-number text-info mb-0">
                            <span id="on_time_rate">0</span>%
                            <span class="font-size-sm font-weight-normal">(<span id="completed_on_time">0</span>/<span id="total_completed">0</span>)</span>
                        </h3>
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
