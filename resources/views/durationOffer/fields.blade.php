@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width:100%;padding: 0 4px;" class="column">
  <div class="row">
    <!-- duration_name Field -->
    <div class="form-group row col-md-6">
      {!! Form::label('duration_name', trans('lang.durations'), ['class' => 'col-4 control-label ']) !!}
      <div class="col-8">
        {!! Form::text('duration_name', Request::is('*edit') ? $duration->duration : null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
      </div>
    </div>
    <!-- duration in number Field -->
    <div class="form-group row col-md-6">
      {!! Form::label('duration_in_number', trans('lang.durations_number'), ['class' => 'col-4 control-label']) !!}
      <div class="col-8">
        {!! Form::text('duration_in_number', Request::is('*edit') ? $duration->duration_in_num : null,  ['class' => 'form-control','placeholder'=>  "Insert Number", 'required']) !!}
      </div>
    </div>
  </div>

  <div class="row">
    <!-- discount Field -->
    <div class="form-group row col-md-6">
      {!! Form::label('discount', trans('lang.discount'), ['class' => 'col-4 control-label ']) !!}
      <div class="col-8">
        {!! Form::text('discount', Request::is('*edit') ? $duration->discount : null,  ['class' => 'form-control','placeholder'=>  "Insert discount in percentage", 'required']) !!}
        <div class="form-text text-muted">
          In % value
        </div>
      </div>
    </div>
    <!-- Description Field -->
    <div class="form-group row " style="display:none">
      {!! Form::label('description', trans("lang.category_description"), ['class' => 'col-3 control-label']) !!}
      <div class="col-9">
        {!! Form::textarea('description', 'ff', ['class' => 'form-control','placeholder'=>
          trans("lang.category_description_placeholder")  ]) !!}
        <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
      </div>
    </div>
  </div>


</div>
@if($customFields)
<div class="clearfix"></div>
<div class="col-12 custom-field-container">
  <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
  {!! $customFields !!}
</div>
@endif

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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} </button>
  <a href="{!! route('durationOffer.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
