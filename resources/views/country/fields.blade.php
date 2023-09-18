@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 100%;padding: 0 4px;" class="column">
  <div class="row">
    <!-- Name Field -->
    
    
    <div class="form-group row col-md-6">
      {!! Form::label('name', trans("lang.category_name"), ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        {!! Form::text('name', Request::is('*edit') ? $country->country_name : null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
        <div class="form-text text-muted">
          Country Name
        </div>
      </div>
    </div>
      <!-- Name Field -->
      </div>

    <div class="row">
      <div class="form-group row col-md-6">
      {!! Form::label('name_en', trans('lang.category_english_name'), ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        {!! Form::text('name_en', Request::is('*edit') ? $country->name_en : null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
        <div class="form-text text-muted">
          Country English Name
        </div>
      </div>
    </div>
  
      <!-- Description Field -->
      <div class="form-group row col-md-6" >
      {!! Form::label('name_ar',  trans('lang.category_arabic_name'), ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        {!! Form::text('name_ar', Request::is('*edit') ? $country->name_ar : null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
        <div class="form-text text-muted">
          Country Arabic Name
        </div>
      </div>
      </div>
    </div>
</div>

@prepend('scripts')
<script type="text/javascript">
    var var15866134771240834480ble = '';
    @if(isset($category) && $category->hasMedia('image'))
    var15866134771240834480ble = {
        name: "{!! $country->getFirstMedia('image')->name !!}",
        size: "{!! $country->getFirstMedia('image')->size !!}",
        type: "{!! $country->getFirstMedia('image')->mime_type !!}",
        collection_name: "{!! $country->getFirstMedia('image')->collection_name !!}"};
    @endif
    var dz_var15866134771240834480ble = $(".dropzone.image").dropzone({
        url: "{!!url('uploads/store')!!}",
        addRemoveLinks: true,
        maxFiles: 1,
        init: function () {
        @if(isset($category) && $category->hasMedia('image'))
            dzInit(this,var15866134771240834480ble,'{!! url($category->getFirstMediaUrl('image','thumb')) !!}')
        @endif
        },
        accept: function(file, done) {
            dzAccept(file,done,this.element,"{!!config('medialibrary.icons_folder')!!}");
        },
        sending: function (file, xhr, formData) {
            dzSending(this,file,formData,'{!! csrf_token() !!}');
        },
        maxfilesexceeded: function (file) {
            dz_var15866134771240834480ble[0].mockFile = '';
            dzMaxfile(this,file);
        },
        complete: function (file) {
            dzComplete(this, file, var15866134771240834480ble, dz_var15866134771240834480ble[0].mockFile);
            dz_var15866134771240834480ble[0].mockFile = file;
        },
        removedfile: function (file) {
            dzRemoveFile(
                file, var15866134771240834480ble, '{!! url("categories/remove-media") !!}',
                'image', '{!! isset($country) ? $country->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
            );
        }
    });
    dz_var15866134771240834480ble[0].mockFile = var15866134771240834480ble;
    dropzoneFields['image'] = dz_var15866134771240834480ble;
</script>
@endprepend
        </div>



<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{ trans('lang.save') }} </button>
  <a href="{!! route('country.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
