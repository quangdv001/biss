<!-- Báo cáo Ads -->
<div class="filter-card">
    <h5 class="mb-4"><i class="flaticon2-filter"></i> Bộ lọc</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Dự án:</label>
                <select class="form-control" id="ads_project_id">
                    <option value="">-- Tìm và chọn dự án --</option>
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
        <div class="col-md-3">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-primary btn-block" onclick="loadAdsReport()">
                    <i class="flaticon2-reload"></i> Xem báo cáo
                </button>
            </div>
        </div>
    </div>
</div>

<div id="ads_report_table"></div>

<hr class="my-8">

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
        <div class="col-md-4">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-primary btn-block" onclick="loadAdsHandlerReport()">
                    <i class="flaticon2-reload"></i> Xem báo cáo theo người xử lý
                </button>
            </div>
        </div>
    </div>
</div>

<div id="ads_handler_report_table"></div>
