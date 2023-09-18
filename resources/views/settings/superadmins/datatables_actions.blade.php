
<div class='btn-group btn-group-sm'>
        @if($id==1)
        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.user_edit')}}"  href="{{ route('superAdminsBoard.edit', $id) }}" disabled="true"  class='btn btn-link'>
        <i class="fa fa-edit"></i> </a>
        @else
        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.user_edit')}}" href="{{ route('superAdminsBoard.edit', $id) }}" class='btn btn-link'>
        <i class="fa fa-edit"></i> </a>
        @endif
        <a data-toggle="tooltip" data-placement="bottom" class='btn btn-link'  class='dropdown-item' href="{{ route('superadmin.profile', ['id'=> $id]) }}"><i class="fa fa-user mr-1"></i></a>

        {!! Form::open(['route' => ['superAdminsBoard.destroy', $id], 'method' => 'delete']) !!}
            {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-link text-danger',
            'onclick' => "return confirm('Are you sure?')"
            ]) !!}
        {!! Form::close() !!}

    
</div>
{!! Form::close() !!}