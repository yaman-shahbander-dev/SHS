{!! Form::open(['route' => ['vendors.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group btn-group-sm'>
    {{--<a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.user_edit')}}" href="{{ route('users.show', $id) }}" class='btn btn-link'>--}}
        {{--<i class="fa fa-eye"></i> </a>--}}
    <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.vendor_edit')}}" href="{{ route('vendors.edit', $id) }}" class='btn btn-link'>
        <i class="fa fa-edit"></i> </a>
    {!! Form::button('<i class="fa fa-trash"></i>', [
    'data-toggle' => 'tooltip',
    'data-placement' => 'bottom',
    'title' => trans('lang.vendor_delete'),
    'type' => 'submit',
    'class' => 'btn btn-link text-danger',
    'onclick' => "swal({title: ".trans('lang.error').", confirmButtonText: ".trans('lang.ok').",
                            text: data.message,type: 'error', confirmButtonClass: 'btn-danger'});"
    ]) !!}
    <a data-toggle="tooltip" data-placement="bottom" title="Vendor Profile" href="{{ route('vendors.profile', ['id'=>$id]) }}" class='btn btn-link'>
        <i class="fa fa-user mr-1"></i> </a>
{{--    <div class="dropdown">--}}
{{--        <a class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">--}}
{{--            <i class="fa fa-cog"></i> </a>--}}
{{--        <div class="dropdown-menu">--}}
{{--            <a class='dropdown-item' href="{{ route('users.login-as-user', $id) }}"> <i class="fa fa-sign-in mr-1"></i> {{trans('lang.vendor_login_as_vendor')}}--}}
{{--            </a>--}}

{{--            <a class='dropdown-item' href="{{ route('vendors.profile', ['id'=>$id]) }}"><i class="fa fa-user mr-1"></i> {{trans('lang.vendor_profile')}} </a>--}}

{{--        </div>--}}
{{--    </div>--}}

</div>
{!! Form::close() !!}