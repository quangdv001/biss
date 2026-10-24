<table>
    <thead>
        <tr>
            <th colspan="4" style="font-size: 16px; font-weight: bold;">Báo cáo Ads dự án: {{ $project->name }}</th>
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
            <th colspan="4" style="font-weight: bold;">Chi tiêu theo chiến dịch</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Chiến dịch</th>
            <th>Đã chi</th>
            <th>Kết quả</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($campaigns as $k => $v)
        <tr>
            <td>{{ $k + 1 }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ number_format($v->total_spend) }}</td>
            <td>{{ number_format($v->total_results) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
