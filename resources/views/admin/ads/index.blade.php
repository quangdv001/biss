@extends('admin.layout.main')
@section('title')
Ads - {{ $project->name }}
@endsection
@section('content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 " id="kt_subheader">
    <div class=" w-100  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <button class="burger-icon burger-icon-left mr-4 d-inline-block d-lg-none" id="kt_subheader_mobile_toggle">
                <span></span>
            </button>
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <h5 class="text-dark font-weight-bold my-1 mr-5">{{ $project->name }} - Ads</h5>
            </div>
        </div>
    </div>
</div>
<!--end::Subheader-->

<div class="content flex-column-fluid" id="kt_content">
    <div class="d-flex flex-row">
        @include('admin.layout.sidebar')
        <!--begin::Content-->
        <div class="flex-row-fluid ml-lg-8" style="min-width: 0; overflow:auto;">
            <div class="card card-custom card-stretch gutter-b">
                <div class="card-header py-3 d-flex justify-content-between">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">Chiến dịch quảng cáo</h3>
                    </div>
                    <div>
                        <a href="javascript:void(0);" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#modalCreateCampaign">
                            <i class="la la-plus"></i> Tạo chiến dịch
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <style>
                        #adsCampaignTable > tbody > tr > td { vertical-align: top; }
                    </style>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="adsCampaignTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên chiến dịch</th>
                                    <th>Kênh</th>
                                    <th>Tổng ngân sách</th>
                                    <th>Tổng đã chi</th>
                                    <th>Còn dư</th>
                                    <th>Link sản phẩm</th>
                                    <th>Kết quả</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($campaigns as $c)
                                <tr data-campaign-row="{{ $c->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="max-width: 220px; word-break: break-word;">
                                        <a href="javascript:void(0);" class="btn-view-detail" data-id="{{ $c->id }}">
                                            {{ $c->name }}
                                        </a>
                                    </td>
                                    <td>{{ $channels[$c->channel] ?? '' }}</td>
                                    <td class="col-total-budget">{{ number_format($c->total_budget) }}</td>
                                    <td class="col-total-spend">{{ number_format($c->total_spend) }}</td>
                                    <td class="col-remaining">{{ number_format($c->remaining) }}</td>
                                    <td style="max-width: 200px; word-break: break-word;">
                                        @if ($c->product_link)
                                        <a href="{{ $c->product_link }}" target="_blank">Link</a>
                                        @endif
                                    </td>
                                    <td class="col-total-results">{{ number_format($c->total_results) }}</td>
                                    <td>
                                        <a href="javascript:void(0);" class="mr-2 btn-edit-campaign"
                                            data-id="{{ $c->id }}" data-name="{{ $c->name }}"
                                            data-channel="{{ $c->channel }}" data-link="{{ $c->product_link }}" data-handler="{{ $c->handler_id }}"
                                            data-start="{{ $c->start_time ? date('Y-m-d', $c->start_time) : '' }}"
                                            data-end="{{ $c->end_time ? date('Y-m-d', $c->end_time) : '' }}">
                                            <i class="la la-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn-remove-campaign" data-id="{{ $c->id }}">
                                            <i class="la la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Chưa có chiến dịch nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Tạo/sửa chiến dịch -->
<div class="modal fade" id="modalCreateCampaign" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chiến dịch quảng cáo</h5>
                <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
            </div>
            <form method="post" action="{{ route('admin.ads.campaign.create') }}">
                @csrf
                <input type="hidden" name="id" value="">
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tên chiến dịch</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Kênh</label>
                        <select class="form-control" name="channel">
                            <option value="">-- Chọn kênh --</option>
                            @foreach ($channels as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Link sản phẩm</label>
                        <input type="text" class="form-control" name="product_link">
                    </div>
                    <div class="form-group">
                        <label>Người xử lý</label>
                        <select class="form-control select2" name="handler_id" style="width: 100%">
                            <option value="">-- Chọn người xử lý --</option>
                            @foreach ($admins as $a)
                            <option value="{{ $a->id }}">{{ $a->username }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <input type="date" class="form-control" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc</label>
                        <input type="date" class="form-control" name="end_time" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Chi tiết chiến dịch -->
<div class="modal fade" id="modalCampaignDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="campaignDetailTitle">Chi tiết chiến dịch</h5>
                </div>
                <div>
                    <a href="javascript:void(0);" id="campaignDetailExportBtn" class="btn btn-success btn-sm font-weight-bolder mr-2" target="_blank">
                        <i class="la la-file-excel-o"></i> Xuất Excel
                    </a>
                    <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
                </div>
            </div>
            <div class="modal-body" id="campaignDetailBody">
                <div class="text-center p-5"><div class="spinner-border" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Thêm/sửa ngân sách -->
<div class="modal fade" id="modalCreateBudget" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nhập ngân sách khách hàng chuyển</h5>
                <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
            </div>
            <form method="post" action="{{ route('admin.ads.budget.create') }}">
                @csrf
                <input type="hidden" name="id" value="">
                <input type="hidden" name="campaign_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Số tiền</label>
                        <input type="number" class="form-control" name="amount" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Thời gian nhập</label>
                        <input type="datetime-local" class="form-control" name="entered_time" required>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <input type="text" class="form-control" name="note">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Thêm/sửa chi tiêu -->
<div class="modal fade" id="modalCreateSpend" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nhập chi tiêu chạy ads</h5>
                <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
            </div>
            <form method="post" action="{{ route('admin.ads.spend.create') }}">
                @csrf
                <input type="hidden" name="id" value="">
                <input type="hidden" name="campaign_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Ngày chạy</label>
                        <input type="date" class="form-control" name="spend_date" required>
                    </div>
                    <div class="form-group">
                        <label>Số tiền chi</label>
                        <input type="number" class="form-control" name="amount" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Kết quả</label>
                        <input type="number" class="form-control" name="results" min="0">
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <input type="text" class="form-control" name="note">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('custom_js')
<script>
    var adsReportUrlTemplate = '{{ route("admin.ads.report", ["campaign_id" => "__ID__"]) }}';
    var adsExportUrlTemplate = '{{ route("admin.ads.export", ["campaign_id" => "__ID__"]) }}';
    var currentCampaignId = null;

    function escapeHtml(str) {
        return String(str === null || str === undefined ? '' : str).replace(/[&<>"']/g, function (s) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[s];
        });
    }

    function formatMoney(n) {
        return Number(n || 0).toLocaleString('vi-VN');
    }

    function pad2(n) {
        return n < 10 ? '0' + n : '' + n;
    }

    function formatDateTime(ts) {
        if (!ts) return '';
        let d = new Date(ts * 1000);
        return pad2(d.getDate()) + '/' + pad2(d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + pad2(d.getHours()) + ':' + pad2(d.getMinutes());
    }

    function toDatetimeLocal(ts) {
        if (!ts) return '';
        let d = new Date(ts * 1000);
        return d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate()) + 'T' + pad2(d.getHours()) + ':' + pad2(d.getMinutes());
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        let parts = dateStr.substring(0, 10).split('-');
        return parts.length === 3 ? (parts[2] + '/' + parts[1] + '/' + parts[0]) : dateStr;
    }

    function renderBudgetRows(budgets) {
        if (!budgets || !budgets.length) {
            return '<tr><td colspan="5" class="text-center text-muted">Chưa có dữ liệu</td></tr>';
        }
        return budgets.map(function (b, i) {
            return '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td>' + formatDateTime(b.entered_time) + '</td>' +
                '<td>' + formatMoney(b.amount) + '</td>' +
                '<td style="max-width: 200px; word-break: break-word;">' + escapeHtml(b.note) + '</td>' +
                '<td>' +
                    '<a href="javascript:void(0);" class="mr-2 btn-edit-budget" data-id="' + b.id + '" data-amount="' + b.amount + '" data-entered="' + toDatetimeLocal(b.entered_time) + '" data-note="' + escapeHtml(b.note) + '"><i class="la la-edit"></i></a>' +
                    '<a href="javascript:void(0);" class="btn-remove-budget" data-id="' + b.id + '"><i class="la la-trash"></i></a>' +
                '</td>' +
            '</tr>';
        }).join('');
    }

    function renderSpendRows(spends) {
        if (!spends || !spends.length) {
            return '<tr><td colspan="5" class="text-center text-muted">Chưa có dữ liệu</td></tr>';
        }
        return spends.map(function (s, i) {
            return '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td>' + formatDate(s.spend_date) + '</td>' +
                '<td>' + formatMoney(s.amount) + '</td>' +
                '<td>' + formatMoney(s.results) + '</td>' +
                '<td>' +
                    '<a href="javascript:void(0);" class="mr-2 btn-edit-spend" data-id="' + s.id + '" data-amount="' + s.amount + '" data-date="' + s.spend_date.substring(0, 10) + '" data-results="' + (s.results || 0) + '" data-note="' + escapeHtml(s.note) + '"><i class="la la-edit"></i></a>' +
                    '<a href="javascript:void(0);" class="btn-remove-spend" data-id="' + s.id + '"><i class="la la-trash"></i></a>' +
                '</td>' +
            '</tr>';
        }).join('');
    }

    function renderCampaignDetail(data) {
        $('#campaignDetailTitle').text('Chi tiết: ' + data.campaign.name);
        $('#campaignDetailExportBtn').attr('href', adsExportUrlTemplate.replace('__ID__', data.campaign.id));

        let html =
            '<div class="row mb-5">' +
                '<div class="col-lg-3"><div class="card card-custom bg-light-primary"><div class="card-body">' +
                    '<div class="font-weight-bold text-muted">Tổng ngân sách</div>' +
                    '<div class="font-size-h3 font-weight-bolder text-primary">' + formatMoney(data.total_budget) + '</div>' +
                '</div></div></div>' +
                '<div class="col-lg-3"><div class="card card-custom bg-light-danger"><div class="card-body">' +
                    '<div class="font-weight-bold text-muted">Tổng đã chi</div>' +
                    '<div class="font-size-h3 font-weight-bolder text-danger">' + formatMoney(data.total_spend) + '</div>' +
                '</div></div></div>' +
                '<div class="col-lg-3"><div class="card card-custom bg-light-success"><div class="card-body">' +
                    '<div class="font-weight-bold text-muted">Còn dư</div>' +
                    '<div class="font-size-h3 font-weight-bolder text-success">' + formatMoney(data.remaining) + '</div>' +
                '</div></div></div>' +
                '<div class="col-lg-3"><div class="card card-custom bg-light-warning"><div class="card-body">' +
                    '<div class="font-weight-bold text-muted">Tổng kết quả</div>' +
                    '<div class="font-size-h3 font-weight-bolder text-warning">' + formatMoney(data.total_results) + '</div>' +
                '</div></div></div>' +
            '</div>' +
            '<div class="row">' +
                '<div class="col-lg-6" style="min-width: 0;">' +
                    '<div class="d-flex justify-content-between align-items-center mb-3">' +
                        '<h5>Các lần nhập ngân sách</h5>' +
                        '<a href="javascript:void(0);" class="btn btn-sm btn-light-primary" data-toggle="modal" data-target="#modalCreateBudget"><i class="la la-plus"></i> Thêm</a>' +
                    '</div>' +
                    '<div class="table-responsive"><table class="table table-bordered table-sm">' +
                        '<thead><tr><th>#</th><th>Thời gian nhập</th><th>Số tiền</th><th>Ghi chú</th><th></th></tr></thead>' +
                        '<tbody>' + renderBudgetRows(data.budgets) + '</tbody>' +
                    '</table></div>' +
                '</div>' +
                '<div class="col-lg-6" style="min-width: 0;">' +
                    '<div class="d-flex justify-content-between align-items-center mb-3">' +
                        '<h5>Chi tiêu theo ngày</h5>' +
                        '<a href="javascript:void(0);" class="btn btn-sm btn-light-primary" data-toggle="modal" data-target="#modalCreateSpend"><i class="la la-plus"></i> Thêm</a>' +
                    '</div>' +
                    '<div class="table-responsive"><table class="table table-bordered table-sm">' +
                        '<thead><tr><th>#</th><th>Ngày chạy</th><th>Số tiền chi</th><th>Kết quả</th><th></th></tr></thead>' +
                        '<tbody>' + renderSpendRows(data.spends) + '</tbody>' +
                    '</table></div>' +
                '</div>' +
            '</div>';

        $('#campaignDetailBody').html(html);
        $('#modalCreateBudget input[name="campaign_id"]').val(data.campaign.id);
        $('#modalCreateSpend input[name="campaign_id"]').val(data.campaign.id);
    }

    function refreshCampaignRow(campaignId, totals) {
        let row = $('[data-campaign-row="' + campaignId + '"]');
        row.find('.col-total-budget').text(formatMoney(totals.total_budget));
        row.find('.col-total-spend').text(formatMoney(totals.total_spend));
        row.find('.col-remaining').text(formatMoney(totals.remaining));
        row.find('.col-total-results').text(formatMoney(totals.total_results));
    }

    function loadCampaignDetail(id) {
        $('#campaignDetailBody').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div></div>');
        $.get(adsReportUrlTemplate.replace('__ID__', id), function (res) {
            if (res.success) {
                renderCampaignDetail(res.data);
                refreshCampaignRow(id, res.data);
            } else {
                init.showNoty(res.message || 'Không tải được dữ liệu!', 'error');
            }
        });
    }

    $(document).on('click', '.btn-view-detail', function () {
        currentCampaignId = $(this).data('id');
        $('#modalCampaignDetail').modal('show');
        loadCampaignDetail(currentCampaignId);
    });

    $('.btn-edit-campaign').click(function () {
        let modal = $('#modalCreateCampaign');
        modal.find('input[name="id"]').val($(this).data('id'));
        modal.find('input[name="name"]').val($(this).data('name'));
        modal.find('select[name="channel"]').val($(this).data('channel'));
        modal.find('input[name="product_link"]').val($(this).data('link'));
        modal.find('select[name="handler_id"]').val($(this).data('handler')).trigger('change');
        modal.find('input[name="start_time"]').val($(this).data('start'));
        modal.find('input[name="end_time"]').val($(this).data('end'));
        modal.modal('show');
    });

    $('#modalCreateCampaign').on('hidden.bs.modal', function () {
        $(this).find('input[name="id"]').val('');
        $(this).find('input[name="name"], input[name="product_link"], input[name="start_time"], input[name="end_time"]').val('');
        $(this).find('select[name="channel"]').val('');
        $(this).find('select[name="handler_id"]').val('').trigger('change');
    });

    $(document).on('click', '.btn-edit-budget', function () {
        let modal = $('#modalCreateBudget');
        modal.find('.modal-title').text('Sửa ngân sách');
        modal.find('input[name="id"]').val($(this).data('id'));
        modal.find('input[name="amount"]').val($(this).data('amount'));
        modal.find('input[name="entered_time"]').val($(this).data('entered'));
        modal.find('input[name="note"]').val($(this).data('note'));
        modal.modal('show');
    });

    $('#modalCreateBudget').on('hidden.bs.modal', function () {
        $(this).find('.modal-title').text('Nhập ngân sách khách hàng chuyển');
        $(this).find('input[name="id"]').val('');
        $(this).find('input[name="amount"], input[name="entered_time"], input[name="note"]').val('');
    });

    $(document).on('click', '.btn-edit-spend', function () {
        let modal = $('#modalCreateSpend');
        modal.find('.modal-title').text('Sửa chi tiêu');
        modal.find('input[name="id"]').val($(this).data('id'));
        modal.find('input[name="spend_date"]').val($(this).data('date'));
        modal.find('input[name="amount"]').val($(this).data('amount'));
        modal.find('input[name="results"]').val($(this).data('results'));
        modal.find('input[name="note"]').val($(this).data('note'));
        modal.modal('show');
    });

    $('#modalCreateSpend').on('hidden.bs.modal', function () {
        $(this).find('.modal-title').text('Nhập chi tiêu chạy ads');
        $(this).find('input[name="id"]').val('');
        $(this).find('input[name="spend_date"], input[name="amount"], input[name="results"], input[name="note"]').val('');
    });

    $('#modalCreateBudget form, #modalCreateSpend form').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function (res) {
                if (res.success) {
                    init.showNoty(res.message || 'Thành công!', 'success');
                    form.closest('.modal').modal('hide');
                    if (currentCampaignId) {
                        loadCampaignDetail(currentCampaignId);
                    }
                } else {
                    init.showNoty(res.message || 'Có lỗi xảy ra!', 'error');
                }
            },
            error: function () {
                init.showNoty('Có lỗi xảy ra!', 'error');
            }
        });
    });

    // Cho phép modal lồng nhau (Thêm/Sửa ngân sách, chi tiêu) hiển thị đúng trên modal Chi tiết
    $(document).on('show.bs.modal', '.modal', function () {
        let zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function () {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
        if ($('.modal:visible').length) {
            $(document.body).addClass('modal-open');
        }
    });

    function removeItem(url, id, onSuccess) {
        Swal.fire({
            title: "Bạn chắc chắn muốn xóa?",
            text: "Sau khi xóa sẽ không thể khôi phục",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy",
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: { id, _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        if (res.success) {
                            init.showNoty('Xóa thành công!', 'success');
                            if (typeof onSuccess === 'function') {
                                onSuccess();
                            } else {
                                setTimeout(() => location.reload(), 500);
                            }
                        } else {
                            init.showNoty(res.message || 'Có lỗi xảy ra!', 'error');
                        }
                    }
                });
            }
        });
    }

    $('.btn-remove-campaign').click(function () {
        removeItem('{{ route("admin.ads.campaign.remove") }}', $(this).data('id'));
    });
    $(document).on('click', '.btn-remove-budget', function () {
        removeItem('{{ route("admin.ads.budget.remove") }}', $(this).data('id'), function () {
            loadCampaignDetail(currentCampaignId);
        });
    });
    $(document).on('click', '.btn-remove-spend', function () {
        removeItem('{{ route("admin.ads.spend.remove") }}', $(this).data('id'), function () {
            loadCampaignDetail(currentCampaignId);
        });
    });
</script>
@endpush
@endsection
