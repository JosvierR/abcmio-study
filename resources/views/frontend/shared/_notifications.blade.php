@if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show success" role="alert">
        <strong>{{trans('notifications.success.label')}}</strong> {{ session()->get('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if(session()->has('message'))
    <div class="alert alert-primary alert-dismissible fade show message" role="alert">
        <strong>{{trans('notifications.success.label')}} </strong> {{ session()->get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if(session()->has('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>{{trans('notifications.important.label')}}</strong> {{ session()->get('warning') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
        <strong>{{trans('notifications.error.label')}}</strong> {{$error}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        @endforeach

    </div>
@endif

