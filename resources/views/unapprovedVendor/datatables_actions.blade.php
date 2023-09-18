{!! Form::open(['route' => ['unapprovedServiceProvider.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group btn-group-sm'>
    <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.user_approve')}}" href="{{ route('approvedServiceProvider.approve', $id) }}" class='btn btn-link'>
        <i class="fa fa-check"></i> </a>

    {!! Form::button('<i class="fa fa-trash"></i>', [
    'data-toggle' => 'tooltip',
    'data-placement' => 'bottom',
    'title' => trans('lang.vendor_delete'),
    'type' => 'submit',
    'class' => 'btn btn-link text-danger',
    'onclick' => "swal({title: ".trans('lang.error').", confirmButtonText: ".trans('lang.ok').",
                            text: data.message,type: 'error', confirmButtonClass: 'btn-danger'});"
    ]) !!}
    



</div>
{!! Form::close() !!}