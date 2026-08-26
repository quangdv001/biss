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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên chiến dịch</th>
                                    <th>Thời gian triển khai</th>
                                    <th>Tổng ngân sách</th>
                                    <th>Tổng đã chi</th>
                                    <th>Còn dư</th>
                                    <th>Người tạo</th>
                                    <th>Người sửa</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($campaigns as $c)
                                <tr class="{{ $activeCampaign && $activeCampaign->id == $c->id ? 'table-primary' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="max-width: 220px; word-break: break-word;">
                                        <a href="{{ route('admin.ads.index', ['id' => $project->id, 'pid' => $pid, 'campaign' => $c->id]) }}">
                                            {{ $c->name }}
                                        </a>
                                    </td>
                                    <td>{{ $c->start_time ? date('d/m/Y', $c->start_time) : '' }} - {{ $c->end_time ? date('d/m/Y', $c->end_time) : '' }}</td>
                                    <td>{{ number_format($c->total_budget) }}</td>
                                    <td>{{ number_format($c->total_spend) }}</td>
                                    <td>{{ number_format($c->remaining) }}</td>
                                    <td>{{ $c->creator->username ?? '' }}</td>
                                    <td>{{ $c->editor->username ?? '' }}</td>
                                    <td>
                                        <a href="javascript:void(0);" class="mr-2 btn-edit-campaign"
                                            data-id="{{ $c->id }}" data-name="{{ $c->name }}"
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

            @if ($activeCampaign)
            <div class="card card-custom card-stretch gutter-b">
                <div class="card-header py-3 d-flex justify-content-between">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">Chi tiết: {{ $activeCampaign->name }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('admin.ads.export', $activeCampaign->id) }}" class="btn btn-success font-weight-bolder">
                            <i class="la la-file-excel-o"></i> Xuất Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-lg-4">
                            <div class="card card-custom bg-light-primary">
                                <div class="card-body">
                                    <div class="font-weight-bold text-muted">Tổng ngân sách</div>
                                    <div class="font-size-h3 font-weight-bolder text-primary">{{ number_format($report['total_budget']) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card card-custom bg-light-danger">
                                <div class="card-body">
                                    <div class="font-weight-bold text-muted">Tổng đã chi</div>
                                    <div class="font-size-h3 font-weight-bolder text-danger">{{ number_format($report['total_spend']) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card card-custom bg-light-success">
                                <div class="card-body">
                                    <div class="font-weight-bold text-muted">Còn dư</div>
                                    <div class="font-size-h3 font-weight-bolder text-success">{{ number_format($report['remaining']) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6" style="min-width: 0;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Các lần nhập ngân sách</h5>
                                <a href="javascript:void(0);" class="btn btn-sm btn-light-primary" data-toggle="modal" data-target="#modalCreateBudget">
                                    <i class="la la-plus"></i> Thêm
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Thời gian nhập</th>
                                            <th>Số tiền</th>
                                            <th>Ghi chú</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($report['budgets'] as $k => $b)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ $b->entered_time ? date('d/m/Y H:i', $b->entered_time) : '' }}</td>
                                            <td>{{ number_format($b->amount) }}</td>
                                            <td style="max-width: 200px; word-break: break-word;">{{ $b->note }}</td>
                                            <td><a href="javascript:void(0);" class="btn-remove-budget" data-id="{{ $b->id }}"><i class="la la-trash"></i></a></td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="text-center text-muted">Chưa có dữ liệu</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-6" style="min-width: 0;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Chi tiêu theo ngày</h5>
                                <a href="javascript:void(0);" class="btn btn-sm btn-light-primary" data-toggle="modal" data-target="#modalCreateSpend">
                                    <i class="la la-plus"></i> Thêm
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ngày chạy</th>
                                            <th>Số tiền chi</th>
                                            <th>Link sản phẩm</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($report['spends'] as $k => $s)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($s->spend_date)->format('d/m/Y') }}</td>
                                            <td>{{ number_format($s->amount) }}</td>
                                            <td style="max-width: 200px; word-break: break-word;">
                                                @if ($s->product_link)
                                                <a href="{{ $s->product_link }}" target="_blank">Link</a>
                                                @endif
                                            </td>
                                            <td><a href="javascript:void(0);" class="btn-remove-spend" data-id="{{ $s->id }}"><i class="la la-trash"></i></a></td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="text-center text-muted">Chưa có dữ liệu</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
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

@if ($activeCampaign)
<!-- Modal: Thêm ngân sách -->
<div class="modal fade" id="modalCreateBudget" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nhập ngân sách khách hàng chuyển</h5>
                <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
            </div>
            <form method="post" action="{{ route('admin.ads.budget.create') }}">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $activeCampaign->id }}">
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

<!-- Modal: Thêm chi tiêu -->
<div class="modal fade" id="modalCreateSpend" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nhập chi tiêu chạy ads</h5>
                <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
            </div>
            <form method="post" action="{{ route('admin.ads.spend.create') }}">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $activeCampaign->id }}">
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
                        <label>Link sản phẩm</label>
                        <input type="text" class="form-control" name="product_link">
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
@endif

@push('custom_js')
<script>
    $('.btn-edit-campaign').click(function () {
        let modal = $('#modalCreateCampaign');
        modal.find('input[name="id"]').val($(this).data('id'));
        modal.find('input[name="name"]').val($(this).data('name'));
        modal.find('input[name="start_time"]').val($(this).data('start'));
        modal.find('input[name="end_time"]').val($(this).data('end'));
        modal.modal('show');
    });

    $('#modalCreateCampaign').on('hidden.bs.modal', function () {
        $(this).find('input[name="id"]').val('');
        $(this).find('input[name="name"], input[name="start_time"], input[name="end_time"]').val('');
    });

    function removeItem(url, id, callback) {
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
                            setTimeout(() => location.reload(), 500);
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
    $('.btn-remove-budget').click(function () {
        removeItem('{{ route("admin.ads.budget.remove") }}', $(this).data('id'));
    });
    $('.btn-remove-spend').click(function () {
        removeItem('{{ route("admin.ads.spend.remove") }}', $(this).data('id'));
    });
</script>
@endpush
@endsection
