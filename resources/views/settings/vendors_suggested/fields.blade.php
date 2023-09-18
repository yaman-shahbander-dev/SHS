@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 100%;padding: 0 4px;" class="column">
    <div class="row">
        <!-- Name Field -->
        <div class="form-group row col-md-6">
            {!! Form::label('name', trans("lang.user_name"), ['class' => 'col-3 control-label']) !!}
            <div class="col-9">
                {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.user_name_placeholder")]) !!}
            </div>
        </div>

        <!-- Email Field -->
        <div class="form-group row col-md-6">
            {!! Form::label('email', trans("lang.user_email"), ['class' => 'col-3 control-label']) !!}
            <div class="col-9">
                {!! Form::text('email', null,  ['class' => 'form-control','placeholder'=>  trans("lang.user_email_placeholder")]) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group row col-md-6">
            {!! Form::label('phone', trans("lang.user_phone"), ['class' => 'col-3 control-label ']) !!}
            <div class="col-9">
                {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  'phone']) !!}
            </div>
        </div>
    </div>


</div>


</div>
@if($customFields)
    {{--TODO generate custom field--}}
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif
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

                var url = _this.closest('.select_cities').data('route');
                //  console.log(url);

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

<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.user')}}</button>
    <a href="{!! route('users.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
