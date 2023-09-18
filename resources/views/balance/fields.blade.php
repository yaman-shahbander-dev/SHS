<div style="flex: 50%;max-width:100%;padding: 0 4px;" class="column">
<div class="row">
  <div class=" col-md-6 row">
    <!-- Select Name-->
    <div class="form-group row col-md-12">
        @if(Request::is('*create'))
              {!! Form::label('nameselect',trans('lang.name'), ['class' => 'col-4 control-label ']) !!}
              <div class="col-8">
                <select name="nameselect" id="brand" aria-controls="dataTableBuilder" class="form-control form-control-sm" required>
                    @foreach($users as $user)
                        <option
                        value="{{ $user->id }}">
                        {{ $user->name }}</option>
                    @endforeach
                </select>
              </div>

        @elseif(Request::is('*edit'))
          {!! Form::label('name', trans('lang.balance_name'), ['class' => 'col-4 control-label  ']) !!}
                <div class="col-8">
                  {!! Form::text('name',$user_name ,  ['class' => 'form-control','placeholder'=>  "Insert Balance", 'readonly']) !!}
                </div>

        @elseif(Request::is('*addBalance'))
              {!! Form::label('name', trans('lang.balance_name'), ['class' => 'col-4 control-label  ']) !!}
              <div class="col-8">
                {!! Form::text('name',$user_name ,  ['class' => 'form-control','placeholder'=>  "Insert Balance", 'readonly']) !!}
              </div>
        @endif
    </div>

    @if(Request::is('*addBalance'))
    <!-- balance Field -->
    <div class="form-group row col-md-12">
        {!! Form::label('balance', trans('lang.balance'), ['class' => 'col-4 control-label  ']) !!}
        <div class="col-8">
          {!! Form::text('balance', Request::is('*edit') ? $balance->balance : null,  ['class' => 'form-control', 'readonly']) !!}
        </div>
    </div>
    @else 
    <!-- balance Field -->
    <div class="form-group row col-md-12">
            {!! Form::label('balance', trans('lang.balance'), ['class' => 'col-4 control-label  ']) !!}
            <div class="col-8">
              {!! Form::text('balance', Request::is('*edit') ? $balance->balance : null,  ['class' => 'form-control', 'placeholder'=>  "Insert Balance", 'required']) !!}
            </div>
        </div>
    @endif
    

    @if(Request::is('*addBalance'))
  <!--add more balance Field -->
  <div class="form-group row col-md-12">
    {!! Form::label('Add', trans('lang.add_amount'), ['class' => 'col-4 control-label  ']) !!}
    <div class="col-8">
      {!! Form::text('Add', null,  ['class' => 'form-control','placeholder'=>  "Insert amount", 'required']) !!}
    </div>
  </div>
  @endif

<!-- Description Field -->
<div class="form-group row " style="display:none">
{!! Form::label('description', trans("lang.category_description"), ['class' => 'col-3 control-label  ']) !!}
<div class="col-9">
{!! Form::textarea('description', 'ff', ['class' => 'form-control','placeholder'=>
  trans("lang.category_description_placeholder")  ]) !!}
<div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
</div>
</div>
</div>
    
    </div>
  </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}}</button>
    <a href="{!! route('balance.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>