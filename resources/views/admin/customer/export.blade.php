<table>
    <thead>
        <tr>
            <th>#</th>
            <th style="width: 200px">Tên</th>
            <th style="width: 100px">SĐT</th>
            <th style="width: 100px">Email</th>
            <th style="width: 100px">Nguồn</th>
            <th style="width: 100px">Trạng thái</th>
            <th style="width: 100px">Phụ Trách</th>
            <th style="width: 400px;">Mô tả</th>
            <th style="width: 400px;">Phản hồi</th>
            <th style="width: 400px;">Ghi chú</th>
            <th style="width: 100px">Tạo lúc</th>
            <th style="width: 100px">Bắt đầu</th>
            <th style="width: 200px">Tiêu đề</th>
            <th style="width: 200px">Tổ chức</th>
            <th style="width: 200px">Thành phố</th>
        </tr>
    </thead>
    <tbody>
        @if ($data->count())
        @foreach ($data as $k => $v)
        <tr>
            <td scope="row">{{ $k + 1 }}</td>
            <td style="word-wrap: break-word">{{ $v->name }}</td>
            <td style="word-wrap: break-word">{{ $v->phone }}</td>
            <td style="word-wrap: break-word">{{ $v->email }}</td>
            <td style="word-wrap: break-word">{{ @$source[$v->source] ?? '' }}</td>
            <td style="word-wrap: break-word">{{ @$status[$v->status]['text'] ?? '' }}</td>
            <td style="word-wrap: break-word">{{ @$v->admin->username }}</td>
            <td style="word-wrap: break-word">{{ $v->description }}</td>
            <td style="word-wrap: break-word">{{ $v->response }}</td>
            <td style="word-wrap: break-word">{{ $v->note }}</td>
            <td style="word-wrap: break-word">{{ $v->created_at ? $v->created_at->format('d/m/Y') : '' }}</td>
            <td style="word-wrap: break-word">{{ $v->start_time ? date('d/m/Y', $v->start_time) : '' }}</td>
            <td style="word-wrap: break-word">{{ $v->title }}</td>
            <td style="word-wrap: break-word">{{ $v->company }}</td>
            <td style="word-wrap: break-word">{{ $v->province }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>