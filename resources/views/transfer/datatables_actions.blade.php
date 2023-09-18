<div class='btn-group btn-group-sm'>
  @can('categories.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('country.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('transfer.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.transfer_edit')}}" href="{{ route('transfer.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan


@can('transfer.destroy')
{!! Form::open(['route' => ['transfer.destroy', $id], 'method' => 'delete']) !!}
{!! Form::button('<i class="fa fa-trash"></i>', [
'type' => 'submit',
'class' => 'btn btn-link text-danger',
'onclick' => "return confirm('Are you sure?')"
]) !!}
{!! Form::close() !!}
@endcan

</div>
