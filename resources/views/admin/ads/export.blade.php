<table>
    <thead>
        <tr>
            <th colspan="4" style="font-size: 16px; font-weight: bold;">Báo cáo chiến dịch: {{ $campaign->name }}</th>
        </tr>
        <tr>
            <td>Link sản phẩm</td>
            <td colspan="3">{{ $campaign->product_link }}</td>
        </tr>
        <tr>
            <td>Thời gian triển khai</td>
            <td colspan="3">{{ $campaign->start_time ? date('d/m/Y', $campaign->start_time) : '' }} - {{ $campaign->end_time ? date('d/m/Y', $campaign->end_time) : '' }}</td>
        </tr>
        @if (!empty($start_date) || !empty($end_date))
        <tr>
            <td>Lọc theo khoảng thời gian</td>
            <td colspan="3">{{ !empty($start_date) ? \Carbon\Carbon::parse($start_date)->format('d/m/Y') : 'Từ đầu' }} - {{ !empty($end_date) ? \Carbon\Carbon::parse($end_date)->format('d/m/Y') : 'Hiện tại' }}</td>
        </tr>
        @endif
        <tr>
            <td>Tổng đã chi</td>
            <td colspan="3">{{ number_format($total_spend) }}</td>
        </tr>
        <tr>
            <td>Ngân sách dự án</td>
            <td colspan="3">{{ number_format($project_budget) }}</td>
        </tr>
        <tr>
            <td>Dự án còn dư</td>
            <td colspan="3">{{ number_format($project_remaining) }}</td>
        </tr>
        <tr>
            <td>Tổng kết quả</td>
            <td colspan="3">{{ number_format($total_results) }}</td>
        </tr>
    </thead>
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
            <th>Kết quả</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($spends as $k => $v)
        <tr>
            <td>{{ $k + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($v->spend_date)->format('d/m/Y') }}</td>
            <td>{{ number_format($v->amount) }}</td>
            <td>{{ number_format($v->results) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
