<div style="flex: 50%;max-width:100%;padding: 0 4px;" class="column">
<div class="row">
  <div class=" col-md-6 row">
    <!-- Select Name-->
    <div class="form-group row col-md-12">
        {!! Form::label('username', trans('lang.banned_user_name'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
        <select name="username" id="brand" aria-controls="dataTableBuilder" class="form-control form-control-sm">
            @if(Request::is('*create'))
              @foreach($users as $user)
                  <option value="{{ $user->id }}" @if(!empty($bannedUser->user_id)) @if( $bannedUser->user_id==$user->id) selected @endif @endif>
                  {{ $user->name }}</option>
              @endforeach
            @endif
            @if(Request::is('*edit'))
              <option value="{{ $bannedUsers->user_id }}">{{ $bannedUsers->user->name }}
              </option>
            @endif
        </select>
            <div class="form-text text-muted">
            Select Name
            </div>
        </div>
    </div>

    



    <!-- Select Ban forever-->
    <div class="form-group row col-md-12">
        {!! Form::label('banValue', trans('lang.ban_forever'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
          <select name="banValue" aria-controls="dataTableBuilder" class="form-control form-control-sm">
            @if(Request::is('*create'))
            <option value="0">0</option>
            <option value="1">1</option>
            @endif
            @if(Request::is('*edit'))
            <option value="0" @if(($bannedUsers->forever_ban) == 0) selected @endif>0
            </option>
            <option value="1" @if(($bannedUsers->forever_ban) == 1) selected @endif>1
            </option>
            @endif
          </select>
            <div class="form-text text-muted">
            Select Value
            </div>
        </div>
    </div>

          <!-- temporary_ban Field -->
  <div class="form-group row col-md-12">
      {!! Form::label('temp_ban', trans('lang.temp_ban'), ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        @if(Request::is('*create'))
          {!! Form::date('temp_ban', null ,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
        @endif
        @if(Request::is('*edit'))
          {!! Form::date('temp_ban', $bannedUsers->temporary_ban ,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
        @endif
        <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
      </div>    
    </div>
    
  </div>



       
 <!-- Description Field -->
  <div class="col-md-6">
    <div class="form-group row col-md-12">
      {!! Form::label('description', trans("lang.category_description"), ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        {!! Form::textarea('description', null, ['class' => 'form-control','style' => 'height: 150px;', 'placeholder'=>
          trans("lang.category_description_placeholder")  ]) !!}
        <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
      </div>
    </div>
  </div>
</div>
      <!-- Submit Field -->
        <div class="form-group col-12 text-right">
          <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}}</button>
          <a href="{!! route('bannedUsers.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
        </div>