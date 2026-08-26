<table>
    <thead>
        <tr>
            <th colspan="4" style="font-size: 16px; font-weight: bold;">Báo cáo chiến dịch: {{ $campaign->name }}</th>
        </tr>
        <tr>
            <td>Thời gian triển khai</td>
            <td colspan="3">{{ $campaign->start_time ? date('d/m/Y', $campaign->start_time) : '' }} - {{ $campaign->end_time ? date('d/m/Y', $campaign->end_time) : '' }}</td>
        </tr>
        <tr>
            <td>Tổng ngân sách</td>
            <td colspan="3">{{ number_format($total_budget) }}</td>
        </tr>
        <tr>
            <td>Tổng đã chi</td>
            <td colspan="3">{{ number_format($total_spend) }}</td>
        </tr>
        <tr>
            <td>Còn dư</td>
            <td colspan="3">{{ number_format($remaining) }}</td>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold;">Chi tiết các lần nhập ngân sách</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Thời gian nhập</th>
            <th>Số tiền</th>
            <th>Ghi chú</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($budgets as $k => $v)
        <tr>
            <td>{{ $k + 1 }}</td>
            <td>{{ $v->entered_time ? date('d/m/Y H:i', $v->entered_time) : '' }}</td>
            <td>{{ number_format($v->amount) }}</td>
            <td>{{ $v->note }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold;">Chi tiết chi tiêu theo ngày</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Ngày chạy</th>
            <th>Số tiền chi</th>
            <th>Link sản phẩm</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($spends as $k => $v)
        <tr>
            <td>{{ $k + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($v->spend_date)->format('d/m/Y') }}</td>
            <td>{{ number_format($v->amount) }}</td>
            <td>{{ $v->product_link }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
