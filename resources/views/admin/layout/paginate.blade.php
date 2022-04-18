<!--begin::Pagination-->
<div class="d-flex justify-content-end align-items-center flex-wrap">
    <div class="d-flex flex-wrap py-2 mr-3">
        @if ($data->onFirstPage())
            <a href="#" class="btn btn-icon btn-sm btn-light mr-2 my-1 disabled"><i class="ki ki-bold-arrow-back icon-xs"></i></a>
        @else
            <a href="{{ $data->previousPageUrl() }}" class="btn btn-icon btn-sm btn-light mr-2 my-1"><i class="ki ki-bold-arrow-back icon-xs"></i></a>
        @endif
        @foreach ($elements as $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $data->currentPage())
                        <a href="#" class="btn btn-icon btn-sm border-0 btn-light btn-hover-primary active mr-2 my-1">{{ $page }}</a>
                    @elseif ($page == 2 && $data->currentPage()!=1)
                        <a href="#" class="btn btn-icon btn-sm border-0 btn-light mr-2 my-1">...</a>
                    @elseif (($page == $data->currentPage() - 1 || $page == $data->currentPage() + 1) || $page == $data->lastPage()|| $page == 1)
                        <a href="{{ $url }}" class="btn btn-icon btn-sm border-0 btn-light mr-2 my-1">{{ $page }}</a>
                    @elseif ($page == $data->lastPage() - 1)
                        <a href="#" class="btn btn-icon btn-sm border-0 btn-light mr-2 my-1">...</a>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($data->hasMorePages())
            <a href="{{ $data->nextPageUrl() }}" class="btn btn-icon btn-sm btn-light mr-2 my-1"><i class="ki ki-bold-arrow-next icon-xs"></i></a>
        @else
            <a href="#" class="btn btn-icon btn-sm btn-light mr-2 my-1 disabled"><i class="ki ki-bold-arrow-next icon-xs"></i></a>
        @endif
    </div>
</div>
<!--end:: Pagination-->
