<div class='btn-group btn-group-sm'>

{{--  @can('review.approve')--}}
  <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('lang.approve_review') }}" href="{{ route('reviews.approve',['id'=> $id]) }}"  class='btn btn-link'>
      <i class="fa fa-check"></i>
    </a>
  
{{--  @endcan--}}

{{--  @can('review.approve')--}}
  <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('lang.approve_edit') }}" href="{{ route('reviews.edit',['id'=> $id]) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
{{--  @endcan--}}




@can('reviews.destroy')
  {!! Form::open(['route' => ['reviews.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
  {!! Form::close() !!}
@endcan

</div>
