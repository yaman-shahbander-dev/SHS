<div class='btn-group btn-group-sm'>


  @can('durationOffer.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.duration_offers_edit')}}" href="{{ route('durationOffer.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan


@can('durationOffer.destroy')
{!! Form::open(['route' => ['durationOffer.destroy', $id], 'method' => 'delete']) !!}
{!! Form::button('<i class="fa fa-trash"></i>', [
'type' => 'submit',
'class' => 'btn btn-link text-danger',
'onclick' => "return confirm('Are you sure?')"
]) !!}
{!! Form::close() !!}
@endcan

</div>
