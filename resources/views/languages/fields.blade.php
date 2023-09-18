<div style="flex: 50%;max-width:100%;padding: 0 4px;" class="column">
     
      <div class="row">
          <!-- Name Field -->
          <div class="form-group row col-md-6">
            {!! Form::label('name', trans("lang.category_name"), ['class' => 'col-3 control-label ']) !!}
            <div class="col-9">
              {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
              <div class="form-text text-muted">
                {{ trans("lang.category_name_help") }}
              </div>
            </div>
          </div>
          <!-- Name Field -->
          <div class="form-group row col-md-6">
            {!! Form::label('shortcut', "Shortcut", ['class' => 'col-3 control-label ']) !!}
            <div class="col-9">
              {!! Form::text('shortcut', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
              <div class="form-text text-muted">
                insert shortcut
              </div>
            </div>
          </div>
      </div>
       
    
</div>
      <!-- Submit Field -->
        <div class="form-group col-12 text-right">
          <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> Save Languages</button>
          <a href="{!! route('language.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
        </div>