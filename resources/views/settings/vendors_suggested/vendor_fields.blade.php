<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
<div style="flex:50%;max-width:100%;padding: 0 4px;" class="column">
    <div class="row">
        <!-- Name Field -->
        <div class="form-group col-md-6 row">
            {!! Form::label('first name', trans("lang.user_name"), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                <input type="text" name="name" value="{{$vendor->name}}" class = "form-control">
            </div>
        </div>

        <!-- username Field -->
        <div class="form-group col-md-6 row">
            {!! Form::label('last name', trans("lang.last_name"), ['class' => 'col-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::text('last_name', null,  ['class' => 'form-control','placeholder'=>  trans('lang.last_name_placeholder')]) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Country Field -->
        <div class="form-group col-md-6 row">
            {!! Form::label('name', trans('lang.country'), ['class' => 'col-3 control-label']) !!}
            <div class="col-md-9">
                <select name="country" id="country" aria-controls="dataTableBuilder" class="form-control form-control-sm">
                    <option value="0" selected> Select</option>

                @foreach($countries as $country)
                        <option value="{{ $country->id }}"  @if(!empty($user->city_id)) @if($country->id==$user->cities->country_id) selected @endif @endif>
                            {{ $country->country_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- City Field -->
        <div class="form-group col-md-6 row">
            {!! Form::label('City', trans('lang.city'), ['class' => 'col-3 control-label']) !!}
            <div class="col-md-9">
                <select name="city" id="city" aria-controls="dataTableBuilder" class="form-control form-control-sm">
                    <option value="0" >select</option>

                @if(!empty($cities))
                        @foreach($cities as $city)
                            <option value={{$city->id}} @if(!empty($user->city_id)) @if($city->id==$user->city_id) selected @endif @endif>
                                {{$city->city_name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Language Field -->
        <div class="form-group col-md-6 row">
            {!! Form::label('Language', trans('lang.app_setting_mobile_language'), ['class' => 'col-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::text('language', null,  ['class' => 'form-control','placeholder'=> trans('lang.app_setting_mobile_language'),'required']) !!}
            </div>
        </div>
        <!-- Delegate Fielad-->

    </div>
    <div class="row">
        <!-- Email Field -->
        <div class="form-group col-md-6 row">
            {!! Form::label('email', trans("lang.user_email"), ['class' => 'col-3 control-label']) !!}
            <div class="col-md-9">
                <input type="text" name="email" value="{{$vendor->email}}" class = "form-control">
            </div>
        </div>

        <!-- Password Field -->
        <div class="form-group col-md-6 row">
            {!! Form::label('password', trans("lang.user_password"), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::password('password', ['class' => 'form-control','placeholder'=>  trans("lang.user_password_placeholder")]) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <!-- phone Fielad-->
        <div class="form-group row col-md-6">
            {!! Form::label('phone', trans("lang.user_phone"), ['class' => 'col-3 control-label ']) !!}
            <div class="col-9">
                <input type="text" name="phone" value="{{$vendor->phone}}" class="form-control">
            </div>
        </div>
        <!-- Suggedted Vendor Field -->
        <div class="form-group col-md-6 row">
            {!! Form::label('name', 'Vendor', ['class' => 'col-3 control-label']) !!}
            <div class="col-md-9">
                <select name="user_id" id="user_id" aria-controls="dataTableBuilder" class="form-control form-control-sm" disabled>
                    
                        <option value="{{ $sugg_user->id }}">{{ $sugg_user->name }}</option>
                    
                </select>
            </div>
        </div>
    </div>

    <!-- $FIELD_NAME_TITLE$ Field -->


    <div class="row">

    <div class="box">


        <div class="content">

            <!-- Custom File Uploader  -->

<div class="left">
    <img id="img-uploaded" class="img2" src="{{asset('storage/Avatar/avatar.png')}}" alt="your image" />
</div>
                                                        <div class="right">

<input type="text" class="img-path newimg" placeholder="Image Path">
<span class="file-wrapper">
  <input type="file" name="avatar" id="imgInp" class="uploader newimg"  />
  <span class="btn2 btn-large btn-alpha">Upload Image</span>
</span>
        </div>

    </div>
</div>

</div>

    @prepend('scripts')
    <script type="text/javascript">
        var user_avatar = '';
        @if(isset($user) && $user->hasMedia('avatar'))
            user_avatar = {
            name: "{!! $user->getFirstMedia('avatar')->name !!}",
            size: "{!! $user->getFirstMedia('avatar')->size !!}",
            type: "{!! $user->getFirstMedia('avatar')->mime_type !!}",
            collection_name: "{!! $user->getFirstMedia('avatar')->collection_name !!}"
        };
        @endif
        var dz_user_avatar = $(".dropzone.avatar").dropzone({
                url: "{!!url('uploads/store')!!}",
                addRemoveLinks: true,
                maxFiles: 1,
                acceptedFiles: ".jpeg,.jpg,.png,.gif",

                init: function () {
                    @if(isset($user) && $user->hasMedia('avatar'))
                    dzInit(this, user_avatar, '{!! url($user->getFirstMediaUrl('avatar','thumb')) !!}')
                    @endif
                },
                accept: function (file, done) {
                    dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                },
                sending: function (file, xhr, formData) {
                    dzSending(this, file, formData, '{!! csrf_token() !!}');
                },
                maxfilesexceeded: function (file) {
                    dz_user_avatar[0].mockFile = '';
                    dzMaxfile(this, file);
                },
                complete: function (file) {
                    dzComplete(this, file, user_avatar, dz_user_avatar[0].mockFile);
                    dz_user_avatar[0].mockFile = file;
                },
                removedfile: function (file) {
                    dzRemoveFile(
                        file, user_avatar, '{!! url("users/remove-media") !!}',
                        'avatar', '{!! isset($user) ? $user->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                    );
                }
            });
        dz_user_avatar[0].mockFile = user_avatar;
        dropzoneFields['avatar'] = dz_user_avatar;
    </script>
    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function () {
            var _this;
            // $('#projectinput1').select2();
            // $('#category').select2()


            $('#country').on('change',function(){
                var _this = $(this);
                $('#city').empty();
                var data = "id="+_this.val();

                var url = _this.closest('.select_cities').data('route');
                  console.log(url);

                $.post(url , data , function(res){
                    $city=['<option value="0" selected="">select </option>'];
                    //   $categoryslt=[];

                    // $menu.push('<option value="none" selected="" disabled="">select Menu Type</option>');

                    for(var i=0 ;i<res.length;i++) {
                        $city.push('<option value="' + res[i]['id'] + '">' + res[i]['city_name'] + '</option>');
                        // $categoryslt.push('<li data-value="' + res[i]['id'] + '" class="option null selected">' + res[i]['name1'] + '</li>');

                    }

                    $('#city').empty();
                    $('#city').append($city);


                });

            });

        });
    </script>
    
    @endprepend


</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.user')}}</button>
    <a href="{!! route('users.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
