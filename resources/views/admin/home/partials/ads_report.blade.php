<!-- Cảnh báo ngân sách chiến dịch theo tháng -->
<h5 class="mb-4"><i class="flaticon2-graph"></i> Cảnh báo ngân sách chiến dịch theo tháng</h5>
<div class="filter-card">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Tháng:</label>
                <input type="month" class="form-control" id="ads_alert_month" value="{{ date('Y-m') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-0">
                <label>Tài khoản quảng cáo:</label>
                <select class="form-control" id="ads_alert_account_filter" style="width: 100%">
                    <option value="">-- Tất cả tài khoản --</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-primary btn-block" onclick="loadAdsBudgetAlertReport()">
                    <i class="flaticon2-reload"></i> Xem báo cáo
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-3">
        <div class="card card-custom bg-light-primary">
            <div class="card-body">
                <div class="font-weight-bold text-muted">Tổng ngân sách nhận</div>
                <div class="font-size-h3 font-weight-bolder text-primary" id="ads_alert_total_budget_stat">0</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-custom bg-light-danger">
            <div class="card-body">
                <div class="font-weight-bold text-muted">Tổng ngân sách đã chi</div>
                <div class="font-size-h3 font-weight-bolder text-danger" id="ads_alert_total_spend_stat">0</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-custom bg-light-warning">
            <div class="card-body">
                <div class="font-weight-bold text-muted">Chiến dịch sắp hết ngân sách</div>
                <div class="font-size-h3 font-weight-bolder text-warning" id="ads_alert_near_limit_count_stat">0</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-custom bg-light-danger">
            <div class="card-body">
                <div class="font-weight-bold text-muted">Chiến dịch vượt ngân sách</div>
                <div class="font-size-h3 font-weight-bolder text-danger" id="ads_alert_over_budget_count_stat">0</div>
            </div>
        </div>
    </div>
</div>

<div id="adsBudgetAlertAccordion">
    <div class="card card-custom gutter-b">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse_ads_total_budget" style="cursor: pointer;">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-graph text-primary"></i> Tổng ngân sách nhận
                    <span class="badge badge-primary badge-lg ml-2" id="ads_alert_total_budget">0</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <span class="svg-icon svg-icon-md"><i class="ki ki-arrow-down"></i></span>
            </div>
        </div>
        <div id="collapse_ads_total_budget" class="collapse" data-parent="#adsBudgetAlertAccordion">
            <div class="card-body" id="ads_total_budget_content">
                <div class="text-center p-5"><p class="text-muted">Đang tải dữ liệu...</p></div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse_ads_total_spend" style="cursor: pointer;">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-graph text-danger"></i> Tổng ngân sách đã chi
                    <span class="badge badge-danger badge-lg ml-2" id="ads_alert_total_spend">0</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <span class="svg-icon svg-icon-md"><i class="ki ki-arrow-down"></i></span>
            </div>
        </div>
        <div id="collapse_ads_total_spend" class="collapse" data-parent="#adsBudgetAlertAccordion">
            <div class="card-body" id="ads_total_spend_content">
                <div class="text-center p-5"><p class="text-muted">Đang tải dữ liệu...</p></div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse_ads_spend_by_account" style="cursor: pointer;">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-pie-chart text-danger"></i> Tổng ngân sách đã chi theo tài khoản quảng cáo
                    <span class="badge badge-danger badge-lg ml-2" id="ads_alert_spend_by_account_count">0</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <span class="svg-icon svg-icon-md"><i class="ki ki-arrow-down"></i></span>
            </div>
        </div>
        <div id="collapse_ads_spend_by_account" class="collapse" data-parent="#adsBudgetAlertAccordion">
            <div class="card-body" id="ads_spend_by_account_content">
                <div class="text-center p-5"><p class="text-muted">Đang tải dữ liệu...</p></div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse_ads_near_limit" style="cursor: pointer;">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-warning text-warning"></i> Chiến dịch sắp hết ngân sách (còn ≤ 500.000đ)
                    <span class="badge badge-warning badge-lg ml-2" id="ads_alert_near_limit_count">0</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <span class="svg-icon svg-icon-md"><i class="ki ki-arrow-down"></i></span>
            </div>
        </div>
        <div id="collapse_ads_near_limit" class="collapse" data-parent="#adsBudgetAlertAccordion">
            <div class="card-body" id="ads_near_limit_content">
                <div class="text-center p-5"><p class="text-muted">Đang tải dữ liệu...</p></div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse_ads_over_budget" style="cursor: pointer;">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="flaticon2-alarm text-danger"></i> Chiến dịch vượt ngân sách
                    <span class="badge badge-danger badge-lg ml-2" id="ads_alert_over_budget_count">0</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <span class="svg-icon svg-icon-md"><i class="ki ki-arrow-down"></i></span>
            </div>
        </div>
        <div id="collapse_ads_over_budget" class="collapse" data-parent="#adsBudgetAlertAccordion">
            <div class="card-body" id="ads_over_budget_content">
                <div class="text-center p-5"><p class="text-muted">Đang tải dữ liệu...</p></div>
            </div>
        </div>
    </div>
</div>

<hr class="my-8">

<!-- Báo cáo Ads theo người xử lý -->
<h5 class="mb-4"><i class="flaticon2-group"></i> Báo cáo theo người xử lý</h5>
<div class="filter-card">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Người xử lý:</label>
                <select class="form-control" id="ads_handler_id">
                    <option value="">Tất cả</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->username }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Dự án:</label>
                <select class="form-control" id="ads_handler_project_id">
                    <option value="">Tất cả dự án</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Từ ngày:</label>
                <input type="date" class="form-control" id="ads_start_time" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Đến ngày:</label>
                <input type="date" class="form-control" id="ads_end_time" value="{{ date('Y-m-d') }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <button type="button" class="btn btn-primary btn-block" onclick="loadAdsHandlerReport()">
                    <i class="flaticon2-reload"></i> Xem báo cáo theo người xử lý
                </button>
            </div>
        </div>
    </div>
</div>

<div id="ads_handler_report_table"></div>

<hr class="my-8">

<!-- Báo cáo theo tài khoản quảng cáo -->
<h5 class="mb-4"><i class="flaticon2-list"></i> Báo cáo theo tài khoản quảng cáo</h5>
<div class="filter-card">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-0">
                <label>Tài khoản quảng cáo:</label>
                <select class="form-control" id="ads_account_filter" style="width: 100%">
                    <option value="">-- Tìm và chọn tài khoản quảng cáo --</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div id="ads_account_empty" class="text-center p-5">
    <p class="text-muted">Chọn tài khoản quảng cáo để xem báo cáo</p>
</div>

<div id="ads_account_report_result" style="display:none;">
    <div class="row mb-5">
        <div class="col-lg-4">
            <div class="card card-custom bg-light-primary">
                <div class="card-body">
                    <div class="font-weight-bold text-muted">Số thẻ thanh toán đã dùng</div>
                    <div class="font-size-h3 font-weight-bolder text-primary" id="ads_account_total_cards">0</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-custom bg-light-success">
                <div class="card-body">
                    <div class="font-weight-bold text-muted">Số dự án đã chạy</div>
                    <div class="font-size-h3 font-weight-bolder text-success" id="ads_account_total_projects">0</div>
                </div>
            </div>
        </div>
    </div>
    <div id="ads_account_cards_table"></div>
</div>
