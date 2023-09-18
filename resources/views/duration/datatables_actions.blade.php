<div class='btn-group btn-group-sm'>
  @can('categories.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('country.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('duration.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.duration_edit')}}" href="{{ route('vendorRegistration.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan
</div>
