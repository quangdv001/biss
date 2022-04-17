<style>
    .pagination li {
        background: #FFFFFF;
        border: 1px solid #DCDCDC;
        width: 32px;
        height: 32px;
        margin-right: 5px;
        margin-left: 5px;
        font-size: 14px;
        line-height: 32px;
        text-transform: uppercase;
    }

    .pagination .ellipsis {
        border: none;
        background: none;
        width: 14px;
    }

    .pagination .active span {
        color: #4D7FF8;
        font-size: 14px;
        cursor: no-drop;
    }

    .pagination .active {
        border: 1px solid #4D7FF8 !important;
    }

    .pagination .disabled span {
        cursor: no-drop;
        color: #999999;
    }

    .pagination li a {
        color: #333333;
        font-size: 14px;
        text-transform: uppercase;
        text-align: center;
        width: 100%;
        display: block;
    }

    .pagination li a span {
        color: #333333;
    }

    .pagination li span {
        color: #999999;
        text-align: center;
        width: 100%;
        display: block;
    }

    .pagination .next i,
    .pagination .prev i {
        font-size: 18px;
    }
</style>
<div class="{{$data->hasPages()?'d-flex':'d-none'}} justify-content-end pagination">
        <ul class="pagination">
            @if ($data->onFirstPage())
                <li class="disabled prev">
                    <span><i class="fa fa-angle-left"></i></span>
                </li>
            @else
                <li class="prev">
                    <a href="{{ $data->previousPageUrl() }}">
                        <span><i class="fa fa-angle-left"></i></span>
                    </a>
                </li>
            @endif
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $data->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @elseif ($page == 2 && $data->currentPage()!=1)
                            <li class="ellipsis"><span>...</span></li>
                        @elseif (($page == $data->currentPage() - 1 || $page == $data->currentPage() + 1) || $page == $data->lastPage()|| $page == 1)
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @elseif ($page == $data->lastPage() - 1)
                            <li class="ellipsis"><span>...</span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            @if ($data->hasMorePages())
                <li class="next">
                    <a  href="{{ $data->nextPageUrl() }}" class="next-button d-flex justify-content-center align-items-center">
                        <span><i class="fa fa-angle-right"></i></span>
                    </a>
                </li>
            @else
                <li class="disabled next">
                    <span><i class="fa fa-angle-right"></i></span>
                </li>
            @endif
        </ul>
    </div>
