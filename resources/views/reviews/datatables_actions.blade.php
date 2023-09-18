<div class='btn-group btn-group-sm'>

{{--  @can('review.approve')--}}
  <a data-toggle="tooltip" data-placement="bottom" title="{{__('lang.approve_review')}}" href="{{ route('approved.edit',['id'=> $id]) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
{{--  @endcan--}}



@can('approved.destroy')
  {!! Form::open(['route' => ['approved.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
  {!! Form::close() !!}
@endcan
</div>
