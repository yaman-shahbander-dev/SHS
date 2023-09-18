<div style="flex: 50%;max-width:100%;padding: 0 4px;" class="column">

    <div class="row">
        <!-- Name Field -->
        <div class="form-group row col-md-6">
          {!! Form::label('name', trans("lang.city_name"), ['class' => 'col-3 control-label text-right']) !!}
          <div class="col-9">
            {!! Form::text('name', Request::is('*edit') ? $city->city_name : null  ,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
            <div class="form-text text-muted">
              {{ trans("lang.category_name_help") }}
            </div>
          </div>
        </div>
          
    
 <!-- Select country-->
        <div class="form-group row col-md-6">
            {!! Form::label('name', trans("lang.country_name"), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
            <select name="country" id="brand" aria-controls="dataTableBuilder" class="form-control form-control-sm" required>
                @foreach($countries as $country)
                    <option  
                    value="{{ $country->id }}" @if(!empty($city->country_id)) @if( $city->country_id==$country->id) selected @endif @endif>
                    {{ $country->country_name }}</option>
                @endforeach
            </select>
                <div class="form-text text-muted">
                Select Country
                </div>
            </div>
        </div>
  </div>
  <div class="row">
       <!-- Name Field -->
        <div class="form-group row col-md-6">
          {!! Form::label('name_en',  trans("lang.category_english_name"), ['class' => 'col-3 control-label text-right']) !!}
          <div class="col-9">
            {!! Form::text('name_en', Request::is('*edit') ? $city->name_en : null  ,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
            <div class="form-text text-muted">
              {{ trans("lang.category_name_help") }}
            </div>
          </div>
        </div>
          <!-- Description Field -->
          <div class="form-group  col-md-6 row " style="">
          {!! Form::label('name_ar', trans("lang.category_arabic_name"), ['class' => 'col-3 control-label text-right']) !!}
          <div class="col-9">
            {!! Form::text('name_ar', Request::is('*edit') ? $city->name_ar : null  ,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
            <div class="form-text text-muted">
              {{ trans("lang.category_name_help") }}
            </div>
          </div>
          </div>
          </div>     
        

      <!-- Submit Field -->
        <div class="form-group col-12 text-right">
          <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i>
          {{ trans('lang.save') }}</button>
          <a href="{!! route('city.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
        </div>