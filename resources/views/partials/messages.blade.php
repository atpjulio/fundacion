@if (\Session::has("message"))
    <div class="alert alert-success" style="background-color: #ffffff !important; color: #00a65a !important;">
        {!! \Session::get("message") !!}
    </div>
@endif
@isset($stored)
    <div class="alert alert-success" style="background-color: #ffffff !important; color: #00a65a !important;">
        {!! $storedMessage !!}
    </div>
@endisset
@if (\Session::has("message_danger"))
    <div class="alert alert-danger" style="background-color: #ffffff !important; color: #dd4b39 !important;">
        {!! \Session::get("message_danger") !!}
    </div>
@endif
@if (\Session::has("message_warning"))
    <div class="alert alert-warning" style="background-color: #ffffff !important; color: #f39c12 !important;">
        {!! \Session::get("message_warning") !!}
    </div>
@endif
@isset($updated)
    <div class="alert alert-warning" style="background-color: #ffffff !important; color: #f39c12 !important;">
        {!! $updatedMessage !!}
    </div>
@endisset
@if (\Session::has("message_info"))
    <div class="alert alert-info" style="background-color: #ffffff !important; color: #004085 !important;">
        {!! \Session::get("message_info") !!}
    </div>
@endif

@if (count($errors))
    <div class="alert alert-danger" style="background-color: #ffffff !important; color: #dd4b39 !important; padding-bottom: 0;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif