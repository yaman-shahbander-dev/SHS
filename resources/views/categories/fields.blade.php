@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 100%;padding: 0 4px;" class="column">
    <div class="row">
    <div class=" col-md-2">
    </div>
      <!-- Name Field -->
      <div class="form-group row col-md-8">
        {!! Form::label('name', trans("lang.category_name"), ['class' => 'col-2 control-label']) !!}
        <div class=" col-9">
          {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
          <div class="form-text text-muted">
            {{ trans("lang.category_name_help") }}
          </div>
        </div>
      </div>
      <!-- NameEn Field -->
    <div class="form-group col-md-12 row">
      <div class="form-group  col-md-6 row">
        {!! Form::label('name_en', trans('lang.category_english_name'), ['class' => 'col-3 control-label ']) !!}
        <div class="col-9">
          {!! Form::text('name_en', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
          <div class="form-text text-muted">
            {{ trans("lang.category_name_help") }}
          </div>
        </div>
        </div>


          <!-- Name ar Field -->
        <div class="form-group col-md-6 row ">
        {!! Form::label('name_ar', trans('lang.category_arabic_name'), ['class' => 'col-3 control-label']) !!}
        <div class="col-9">
          {!! Form::text('name_ar', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
          <div class="form-text text-muted">
            {{ trans("lang.category_name_help") }}
          </div>
        </div>
      </div>
      </div>



  <div class="row">
    <!-- Description Field -->
    <div class="form-group row col-md-6">
      {!! Form::label('description', trans("lang.category_description"), ['class' => 'col-3 control-label']) !!}
      <div class="col-9">
      {!! Form::textarea('description', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'style' => 'height:100px', 'required']) !!}
        <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
      </div>
    </div>
    <!-- Description AR Field -->
    <div class="form-group row col-md-6">
          {!! Form::label('description_ar', trans('lang.category_arabic_desc'), ['class' => 'col-3 control-label ']) !!}
          <div class="col-9">
          {!! Form::textarea('description_ar', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'style' => 'height:100px', 'required']) !!}
            <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
          </div>
        </div>
  </div>
  <div class="row">
        <!-- Description En Field -->
        <div class="form-group row col-md-6">
          {!! Form::label('description_en', trans('lang.category_english_desc'), ['class' => 'col-3 control-label ']) !!}
          <div class="col-9">
            {!! Form::textarea('description_en', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'style' => 'height:100px', 'required']) !!}
            <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
          </div>
        </div>
         <!-- Image Field -->


  <!-- $FIELD_NAME_TITLE$ Field -->
  <div class="form-group row col-md-6">
        {!! Form::label('categoryImage', trans('lang.image'), ['class' => 'col-md-3 control-label', 'style' => 'font-size:15px']) !!}

            <div class="col-md-9">
                <!-- $FIELD_NAME_TITLE$ Field -->
                <div class="row">
                    <div class="box">
                        <div class="content">

                <!-- Custom File Uploader  -->

                    <div class="left">
                      @if(Request::is('*edit') && $category->image != null)
                        <img id="img-uploaded" class="img2" src="{{asset('storage/categoriesPic' ."/" . $category->image)}}" alt="your image" />
                      @else
                        <img id="img-uploaded" class="img2" src="{{asset('storage/categoriesPic/default.png')}}" alt="your image" />
                      @endif
                    </div>

                     <div class="right">

                      <input type="text" class="img-path newimg" placeholder="Image Path">
                      <span class="file-wrapper">
                      <input type="file" name="categoryImage" id="imgInp" class="uploader newimg"  />
                      <span class="btn2 btn-large btn-alpha">Upload Image</span>
                     </span>
                </div>
            </div>
        </div>
    </div>



  @prepend('scripts')
  <script type="text/javascript">
      var var15866134771240834480ble = '';
      @if(isset($category) && $category->hasMedia('image'))
      var15866134771240834480ble = {
          name: "{!! $category->getFirstMedia('image')->name !!}",
          size: "{!! $category->getFirstMedia('image')->size !!}",
          type: "{!! $category->getFirstMedia('image')->mime_type !!}",
          collection_name: "{!! $category->getFirstMedia('image')->collection_name !!}"};
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
                  'image', '{!! isset($category) ? $category->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
              );
          }
      });
      dz_var15866134771240834480ble[0].mockFile = var15866134771240834480ble;
      dropzoneFields['image'] = dz_var15866134771240834480ble;
  </script>
  @endprepend
  </div>
  </div>





</div>
      </div>


<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.category')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
