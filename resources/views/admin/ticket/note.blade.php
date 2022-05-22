@if($notes->count() > 0)
@foreach($notes as $v)
<div class="timeline-item">
    <div class="timeline-media">
        <img alt="Pic"
            src="{{ @$v->admin->avatar ? Storage::url($v->admin->avatar) : '/assets/admin/themes/assets/media/users/default.jpg' }}" />
    </div>
    <div class="timeline-content">
        <div
            class="d-flex align-items-center justify-content-between mb-3">
            <div class="mr-2">
                <a href="#"
                    class="text-dark-75 text-hover-primary font-weight-bold">
                    {{ @$v->admin->username }}
                </a>
                <span class="text-muted ml-2">
                    {{ $v->created_at->diffForHumans() }}
                </span>
                {{-- <span
                    class="label label-light-success font-weight-bolder label-inline ml-2">new</span> --}}
            </div>
        </div>
        <p class="p-0">
            {!! nl2br($v->note) !!}
        </p>
    </div>
</div>
@endforeach
@endif