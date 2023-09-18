<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
        <!-- Vendors Field -->
        @if(!Request::is('*edit'))
        @if(empty($url))
        <div class="form-group row">
            {!! Form::label('vendors', trans('lang.vendors'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
            <select name="vendors" id="brand" aria-controls="dataTableBuilder" class="form-control form-control-sm" required @if(Request::is('*edit')) disabled @endif>
              <option value="0">select</option>
                @foreach($vendors as $vendor)
                  <option value="{{ $vendor->id }}" @if(Request::is('*edit')) @if($offer->user_id == $vendor->id) selected @endif @endif>{{ $vendor->name }}</option>
                 @endforeach
              </select>
              <div class="form-text text-muted">
                Select Vendor
              </div>
            </div>
          </div>
        @else
        <div class="form-group row">
            {!! Form::label('vendors', trans('lang.vendors'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
            <select name="vendors" id="brand" aria-controls="dataTableBuilder" class="form-control form-control-sm" required @if(Request::is('*edit')) disabled @endif>
                  <option value="{{ $vendors->id }}" selected >{{ $vendors->name }}</option>
            </select>
            </div>
          </div>
         @endif
        @endif

          <!-- Name Field -->
          <div class="form-group row ">
            {!! Form::label('offername', trans('lang.offer_name'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
              {!! Form::text('offername', Request::is('*edit') ? $offer->title : null ,  ['class' => 'form-control', 'required' => true, 'placeholder'=>  trans("lang.category_name_placeholder"), 'required']) !!}
              <div class="form-text text-muted">
                {{ trans("lang.category_name_help") }}
              </div>
            </div>
          </div>

          <!-- Description Field -->

          <div class="form-group row ">
            {!! Form::label('description', trans('lang.description'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
              {!! Form::text('description', Request::is('*edit') ? $offer->description : null ,  ['class' => 'form-control', 'required' => true, 'placeholder'=>  "Insert Description", 'required']) !!}
              <div class="form-text text-muted">
                 Insert Description
              </div>
            </div>
          </div>

          <!-- Select category-->
        <div class="form-group row">
              {!! Form::label('category', trans('lang.category'), ['class' => 'col-3 control-label text-right']) !!}
              <div class="col-9">
              <select name="category" id="category" aria-controls="dataTableBuilder" class="form-control form-control-sm" required>
              <option value="0">select</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" @if (Request::is('*edit')) @if(!empty($category->id)) @if($offer->subcategories->categories->id==$category->id) selected @endif @endif @endif>{{ $category->name }}</option>
                @endforeach
              </select>
               <div class="form-text text-muted">
                 select category
               </div>
              </div>
          </div>

        <!-- Select subcategory-->
        <div class="form-group row " @if(Request::is('*edit')) style="visibility:visible" @else style="visibility:hidden" @endif id="sub">
            {!! Form::label('subcategory', trans('lang.subcategory'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9 subcategory">
              <select name="subcategory" id="subcategory" aria-controls="dataTableBuilder" class="form-control form-control-sm" required>
              <option value="" >select</option>
                @if(Request::is('*edit'))
                    @foreach($subcategories as $subcategory)
                            <option value={{$subcategory->id}} @if(!empty($subcategory->id)) @if($offer->subcategory_id==$subcategory->id) selected @endif @endif>
                                {{$subcategory->name}}</option>
                    @endforeach
                @endif
              </select>
              <div class="form-text text-muted">
                 select subcategory
               </div>
            </div>
        </div>

        </div>
        <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

        <!-- image Field -->

        <div class="form-group row">
        {!! Form::label('image', trans('lang.image'), ['class' => 'col-md-3 control-label', 'style' => 'font-size:15px']) !!}
            <div class="col-md-9">

                <!-- $FIELD_NAME_TITLE$ Field -->
                <div class="row">
                    <div class="box">
                        <div class="content">

                <!-- Custom File Uploader  -->

                    <div class="left">
                        @if(Request::is('*edit'))
                            @if($offer->image != null )
                                <img id="img-uploaded" class="img2" src="{{asset('storage/specialOffersPic' . "/" . $offer->image)}}" alt="your image" />
                            @endif
                        @else
                            <img id="img-uploaded" class="img2" src="{{asset('storage/specialOffersPic/default.png')}}" alt="your image" />
                        @endif

                    </div>

                     <div class="right">

                      <input type="text" class="img-path newimg" placeholder="Image Path">
                      <span class="file-wrapper">
                      <input type="file" name="image" id="imgInp" class="uploader newimg"  />
                      <span class="btn2 btn-large btn-alpha">Upload Image</span>
                     </span>
                </div>
            </div>
        </div>

        </div>
     </div>
  </div>
          @prepend('scripts')

          <script>
            $(document).ready(function () {
                var var1;

                $('#category').on('change',function(){
                    var var1 = $(this);
                    $('#subcategory').empty();
                    $("#sub").css('visibility', "visible");

                    var data = "id="+var1.val();

                    var url = $('.subcategory').data('route');


                    $.post(url , data , function(res){
                        $subcategory=['<option value="0" selected="">select </option>'];
                        //   $categoryslt=[];

                        // $menu.push('<option value="none" selected="" disabled="">select Menu Type</option>');

                        for(var i=0 ;i<res.length;i++) {
                            $subcategory.push('<option value="' + res[i]['id'] + '">' + res[i]['name'] + '</option>');
                            // $categoryslt.push('<li data-value="' + res[i]['id'] + '" class="option null selected">' + res[i]['name1'] + '</li>');

                        }

                        $('#subcategory').empty();
                        $('#subcategory').append($subcategory);
                        refsubcategory();


                    });

                });

            });
          </script>



          @endprepend
        </div>

      <!-- Submit Field -->
        <div class="form-group col-12 text-right">
          <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> Save Offer</button>
          <a href="{!! route('city.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
        </div>
