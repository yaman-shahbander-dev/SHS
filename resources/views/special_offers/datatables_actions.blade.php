<div class='btn-group btn-group-sm'>
  @can('categories.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('offers.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('offers.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('lang.edit_offer') }}" 
  href="{{ route('offers.edit', $id) }}" class='btn btn-link'>
  <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('offers.destroy')
{!! Form::open(['route' => ['offers.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
