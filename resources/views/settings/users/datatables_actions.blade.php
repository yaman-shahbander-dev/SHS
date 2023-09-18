{!! Form::open(['route' => ['users.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group btn-group-sm'>
        @if($id==1)
        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.user_edit')}}"  href="{{ route('users.edit', $id) }}" disabled="true"  class='btn btn-link'>
        <i class="fa fa-edit"></i> </a>
        @else
        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.user_edit')}}" href="{{ route('users.edit', $id) }}" class='btn btn-link'>
        <i class="fa fa-edit"></i> </a>
        @endif
        <a data-toggle="tooltip" data-placement="bottom" class='btn btn-link'  class='dropdown-item' href="{{ route('user.profile', ['id'=>$id]) }}"><i class="fa fa-user mr-1"></i></a>

        @if($id==1)
            {!! Form::button('<i class="fa fa-trash"></i>', [
            'data-toggle' => 'tooltip',
            'data-placement' => 'bottom',
            'disabled'=>'disabled',
            'title' => trans('lang.user_delete'),
            'type' => 'submit',
            'class' => 'btn btn-link text-danger',
            'onclick' => "swal({title: ".trans('lang.error').", confirmButtonText: ".trans('lang.ok').",
                                    text: data.message,type: 'error', confirmButtonClass: 'btn-danger'});"
            ]) !!}
        @else
            {!! Form::button('<i class="fa fa-trash"></i>', [
            'data-toggle' => 'tooltip',
            'data-placement' => 'bottom',
            'title' => trans('lang.user_delete'),
            'type' => 'submit',
            'class' => 'btn btn-link text-danger',
            'onclick' => "return confirm('Are you sure?')"
            ]) !!}
@endif
    
</div>
{!! Form::close() !!}