<div style="flex: 50%;padding: 0 4px;" class="column">

        <!-- Select type-->
        <div class="form-group row ">
        <div class="col-6 row">
            {!! Form::label('type', trans('lang.type'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
            <select name="type" aria-controls="dataTableBuilder" class="form-control form-control-sm">
               <option value="1" @if(Request::is("*edit")) @if($notification->type == 1) selected @endif @endif>Service Providers</option>
               <option value="2" @if(Request::is("*edit")) @if($notification->type == 2) selected @endif @endif>Home Owners</option>
            </select>
            </div>
            </div>
        </div>

        <!-- Select country-->
        <div class="form-group row ">
        <div class="col-6 row">
            {!! Form::label('country', trans('lang.country'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
            <select name="country" id="country" aria-controls="dataTableBuilder" class="form-control form-control-sm">
            <option value="0">select</option>
              @foreach($countries as $country)
                <option value="{{ $country->id }}" @if(Request::is('*edit')) @if($notification->country == $country->id) selected @endif @endif>{{ $country->country_name }}</option>
              @endforeach
            </select>
            </div>
            </div>
            <div class="col-6 row cityShow" @if(Request::is('*edit')) @if($notification->country!=Null) @else style="display:none" @endif @endif >
            {!! Form::label('city', trans('lang.city'), ['class' => 'col-3 control-label text-right']) !!}
              <div class="col-9">
                <select name="city" id="city" aria-controls="dataTableBuilder" class="form-control form-control-sm">
                @if(Request::is('*edit'))
                  @foreach($cities as $city)
                          <option value={{$city->id}} @if(!empty($city->id)) @if($notification->city==$city->id) selected @endif @endif>
                              {{$city->city_name}}</option>
                  @endforeach
                @endif
                </select>
              </div>
            </div>
        </div>

        <!-- Select city-->

      <!-- Select category-->
      <div class="form-group row ">
        <div class="col-6 row">
            {!! Form::label('category', trans('lang.category'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
            <select name="category" id="category" aria-controls="dataTableBuilder" class="form-control form-control-sm">
            <option value="0">select</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @if(Request::is('*edit')) @if($notification->category == $category->id) selected @endif @endif>{{ $category->name }}</option>
              @endforeach
            </select>
            </div>
            </div>
        </div>


        <!-- Select subcategory-->
        <div class="form-group row ">
        <div class="col-6 row">
            {!! Form::label('subcategory', trans('lang.subcategory'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
            <select name="subcategory" id="subcategory" aria-controls="dataTableBuilder" class="form-control form-control-sm">
            <option value="" >select</option>
            @if(Request::is('*edit'))
                @foreach($subcategories as $subcategory)
                        <option value={{$subcategory->id}} @if(!empty($subcategory->id)) @if($notification->subcategory==$subcategory->id) selected @endif @endif>
                            {{$subcategory->name}}</option>
                @endforeach
            @endif
            </select>
            </div>
            </div>
        </div>

          <!-- title Field -->
          <div class="form-group row ">
          <div class="col-6 row">
            {!! Form::label('title', trans('lang.notification_title'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
              {!! Form::text('title', Request::is('*edit') ? $notification->title : null   ,  ['class' => 'form-control','placeholder'=>  'Insert title']) !!}
            </div>
            </div>
          </div>

          <!-- body Field -->
          <div class="form-group row ">
          <div class="col-6 row">
            {!! Form::label('description', trans('lang.body'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
              {!! Form::text('description', Request::is('*edit') ? $notification->body : null  ,  ['class' => 'form-control','placeholder'=>  'Insert Description']) !!}
            </div>
            </div>
          </div>

        </div>

        </div>
        <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">


          @prepend('scripts')
            


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

                    var url = _this.closest('.card-body').data('route');
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
                        $('.cityShow').removeAttr('style');

                    });

                });

            });



        </script>
        <script>

        $(document).ready(function () {
                var var1;
                // $('#projectinput1').select2();
                // $('#category').select2()


                $('#category').on('change',function(){
                    var var1 = $(this);
                    $('#subcategory').empty();
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


                    });

                });

            });
        </script>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js">
                </script>
                <script src="https://www.gstatic.com/firebasejs/8.2.2/firebase-app.js"></script>
                <script src="https://www.gstatic.com/firebasejs/8.2.2/firebase-messaging.js"></script>

                <script>


                    // var firebaseConfig = {
                    //     apiKey: "AIzaSyDNBLqF0AtzyJyUi0aMxBfQfBrGnANwp7A",
                    //     authDomain: "chat-app-79620.firebaseapp.com",
                    //     projectId: "chat-app-79620",
                    //     storageBucket: "chat-app-79620.appspot.com",
                    //     messagingSenderId: "1028100173097",
                    //     appId: "1:1028100173097:android:a19b6cc6f693cda1905b38"
                    // };

                    var firebaseConfig = {
                        apiKey: "AIzaSyC1GWjZ1Irhj7_OB4Ob--_a_rcP0xnk1Js",
                        authDomain: "shs-chat-c425e.firebaseapp.com",
                        databaseURL: "https://shs-chat-c425e-default-rtdb.firebaseio.com/",
                        projectId: "shs-chat-c425e",
                        storageBucket: "shs-chat-c425e.appspot.com",
                        messagingSenderId: "963124896977",
                        appId: "1:963124896977:web:016e3a562edc51652211f0",
                        measurementId: "G-2MVVRHDF8M"
                    };

                    firebase.initializeApp(firebaseConfig);
                    const messaging = firebase.messaging();

                    messaging.onMessage(function(payload) {
                        const noteTitle = payload.notification.title;
                        const noteOptions = {
                            body: payload.notification.body,
                            icon: payload.notification.icon,
                        };
                        new Notification(noteTitle, noteOptions);
                    });

                </script>

          @endprepend
        </div>

      <!-- Submit Field -->
        <div class="form-group col-12 text-right">
          <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}}</button>
          <a href="{!! route('notification.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
        </div>
