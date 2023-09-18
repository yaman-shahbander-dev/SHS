<div class='btn-group btn-group-sm'>
  @can('bannedUsers.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.banned_user_detail')}}" href="{{ route('bannedUsers.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('bannedUsers.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.banned_user_edit')}}" href="{{ route('bannedUsers.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  <a data-toggle="tooltip" data-placement="bottom" class='btn btn-link'  class='dropdown-item' href="{{ route('showBannedProfile', ['id' => $user_id]) }}"><i class="fa fa-user mr-1"></i></a>

  @can('bannedUsers.destroy')
{!! Form::open(['route' => ['bannedUsers.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
