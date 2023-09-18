<div class='btn-group btn-group-sm'>
  @can('categories.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('subcategory.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('notification.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.edit_notification')}}" 
  href="{{ route('notification.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('notification.destroy')
{!! Form::open(['route' => ['notification.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>