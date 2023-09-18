  <div style="flex: 50%;max-width: 100%;padding: 0 4px;" class="column">
    <div class="row">
      <!-- Name Field -->
      <div class="form-group col-md-6">
        {!! Form::label('type', 'Type', ['class' => 'col-3 control-label']) !!}
        <div class="col-10">
          {!! Form::text('type',  null  ,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
          <div class="form-text text-muted">
            {{ trans("lang.category_name_help") }}
          </div>
        </div>
      </div>
      <!-- duration Field -->
      <div class="form-group col-md-6">
        {!! Form::label('duration', 'Duration', ['class' => 'col-3 control-label']) !!}
        <div class="col-10">
          {!! Form::text('duration',  null  ,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
          <div class="form-text text-muted">
            {{ trans("lang.category_name_help") }}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- discount Field -->
      <div class="form-group col-md-6">
      {!! Form::label('discount', 'Discount', ['class' => 'col-3 control-label ']) !!}
      <div class="col-10">
        {!! Form::text('discount',  null  ,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
        <div class="form-text text-muted">
          {{ trans("lang.category_name_help") }}
        </div>
      </div>
    </div>
    </div>
          
  </div>
       

<!-- Submit Field -->
  <div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> Save City</button>
    <a href="{!! route('city.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
  </div>