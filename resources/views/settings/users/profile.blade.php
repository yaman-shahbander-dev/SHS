@extends('layouts.app')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/nice-select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/mystyle.css')}}">

@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{!! trans('lang.user_profile') !!} <small>{{trans('lang.media_desc')}}</small></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('lang.user_profile')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-user mr-2"></i> {{trans('lang.user_about_me')}}</h3>
                        </div>
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img src="{{$user->getFirstMediaUrl('avatar','icon')}}" class="profile-user-img img-fluid img-circle" alt="{{$user->name}}">
                            </div>
                            <h3 class="profile-username text-center">{{$user->name}}</h3>

                            <p class="text-muted text-center">{{implode(', ',$rolesSelected)}}</p>
                            <a class="btn btn-outline-{{setting('theme_color')}} btn-block" href="mailto:{{$user->email}}"><i class="fa fa-envelope mr-2"></i>{{$user->email}}
                            </a>
                        </div>
    <!-- Blocking -->
        <div class="row">
            <div class="content col-md-12">



                <div class="view"><a class="btn btn-danger ban_style_user" > @if($user->bannedusers!=null){{trans('lang.edit_block')}} @else {{trans('lang.block_user')}} @endif</a></div>
                @if($user->bannedusers!=null)
                {!! Form::open(['route' => ['unBlockUser', 'id' => $user->id], 'method' => 'delete']) !!}

            
                <div class="view"><input type="submit" onclick="confirm('Are you sure?')" class="btn btn-primary unban_style_user" value="Unblock User"></div>

                {!! Form::close() !!}
                @endif
            </div>
            <div class="hidden-content col-md-12">
                <form action="{{route('saveUpdateBlockedUser',['id' => $user->id])}}" method="POST">
                   @csrf
                <div class="row">
                    <!-- Select Ban forever-->
                    <div class="col-md-1"></div>
                    <div class="form-group row col-md-11">
                   
                        <div class=" col-md-11">
                    
                    <div class="page__section page__custom-settings">

                    <div class="page__toggle">
                    <label class="toggle">
                    <input class="toggle__input" onclick="valueChanged()"  name="forever"  type="checkbox" {{$user->bannedusers!=null &&$user->bannedusers->forever_ban==1 ? 'checked' : ''}}>
                    <span class="toggle__label">
                    <span class="toggle__text">{{trans('lang.ban_forever')}}</span>
                    </span>
                    </label>
                    </div>

                    </div>

                      
                       
                        </div>
                    </div>
                    <!-- temporary_ban Field -->
                    <div class="col-md-1"></div>
                    <div class="form-group row col-md-11   temp_hide_show">
                        {!! Form::label('temp_ban', trans('lang.temp_ban'), ['class' => ' control-label']) !!}
                        <div class="col-md-11">
                            {!! Form::date('temp_ban', $user->bannedusers!=null && $user->bannedusers->temporary_ban!= '0000-00-00' ? $user->bannedusers->temporary_ban : '' ,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}

                            <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="form-group row col-md-11">
                        <!-- Description Field -->
                        <div class="form-group row col-md-12">
                            {!! Form::label('description', trans("lang.category_description"), ['class' => 'control-label','required']) !!}
                            <div class="col-12">
                                {!! Form::textarea('Ban_description', $user->bannedusers!=null && $user->bannedusers->description!= '0000-00-00' ? $user->bannedusers->description : '' , ['class' => 'form-control','style' => 'height: 150px;', 'placeholder'=>
                                trans("lang.category_description_placeholder")  ]) !!}
                                <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
                            </div>
                        </div>
                    </div>
                </div>  <div class="row">
                    <div class="col-md-1"></div>
                    <div class="form-group row col-md-11">
                        <!-- Description Field -->
                        <div class="form-group row col-md-12">
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.user')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
           
           
           
           
                </form>
            </div>
        </div>

        <!-- /.card-body -->
                    </div>

                    <!-- available-->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-list mr-2"></i>{{ trans('lang.residence_city') }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ">
                            <div class="row">
                                    <div class="col-md-6" style="margin-bottom: 8px;">
                                        <strong>{{ $user->cities->city_name}}</strong>
                                    </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- favorites-->
                    <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-list mr-2"></i>{{trans('lang.favorite_service_providers')}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                 <div class="row">
                                @foreach($user->vendorFavorite as $favorite)
                                        <div class="col-md-12">
                                        <strong><a data-toggle="tooltip" data-placement="bottom" title="Vendor Profile" href="{{ route('vendors.profile', ['id'=>$favorite->id]) }}" class='btn btn-link'>
                                        <i class="fa fa-user mr-2"></i>{{ $favorite->name }} </a></strong>
                                        </div>
                                @endforeach
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    @include('flash::message')
                    @include('adminlte-templates::common.errors')
                    <div class="clearfix"></div>
                    <div class="card">

                        <div class="card-header">
                            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-cog mr-2"></i>{{trans('lang.app_setting')}}</a>
                                </li>
                                @hasrole('client')
                                <div class="ml-auto d-inline-flex">
                                    <li class="nav-item">
                                        <a class="nav-link pt-1" href="#"><i class="fa fa-check-o"></i> {{trans('lang.app_setting_become_restaurant_owner')}}</a>
                                    </li>
                                </div>
                                @endhasrole
                            </ul>
                        </div>

                        <div class="card-body" data-route="{{url('api/user/select')}}">
                            {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}
                            <div class="row">
                                @include('settings.users.fields')
                            </div>
                            {!! Form::close() !!}
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
            <div class="card-body own-padding" style="position: relative; left: 300px;">
                    <div class="container mt-5 mb-5">
                        <div class="row height d-flex justify-content-center align-items-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="p-3">
                                        <h6>{{ trans('lang.my_reviews') }} <span style="font-size: 12px;color: #777;">({{trans('lang.all_reviews_user_for_vendors')}})</span></h6>
                                    </div>
                                    <div class="mt-2">

                @foreach($user->vendors as $client)
                    @if($client->pivot->approved==1)
                        <?php
                        $sum = 0;
                        $sum += $client->pivot->price_rating+$client->pivot->service_rating+$client->pivot->trust_rating+$client->pivot->speed_rating+$client->pivot->knowledge_rating;
                        if($sum!=0)

                    $sum=$sum/(5);
                        ?>
                    <div class="d-flex flex-row p-3" style="display: block !important;">

                    <div class="pic-name">
                    <img src="https://i.imgur.com/agRGhBc.jpg" width="40" height="40" class="rounded-circle mr-3" style="float:left">
                    <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-row align-items-center"> <span class="mr-2">{{$client->name}}</span> <div id="fixture" >{{$sum}}</div> </div> <small>{{$client->pivot->created_at}}</small>
                            </div>
                    </div>

                        <div class="w-100">
                        </div>
                            <p class="text-justify comment-text mb-0">{{$client->pivot->description}}</p>
                            <div class="d-flex flex-row user-feed" >
                                 <div class="space">
                                    <span class="wish">{{trans('lang.Rating_Service')}}</span>
                                    <div id="fixture">{{$client->pivot->service_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;{{trans('lang.Rating_Price')}}</span>
                                    <div id="fixture">{{$client->pivot->price_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;{{trans('lang.Rating_Speed')}}</span>
                                    <div id="fixture">{{$client->pivot->speed_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;{{trans('lang.Rating_Trust')}}</span>
                                    <div id="fixture">{{$client->pivot->trust_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;{{trans('lang.Rating_Knowledge')}}</span>
                                    <div id="fixture">{{$client->pivot->knowledge_rating}}</div>
                                 </div>


                        </div>
                    </div>
                                            @endif
    @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
        </div>
    </section>
    @include('layouts.media_modal',['collection'=>null])
@endsection
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- select2 -->
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>
    <script>

function showPreviewOne(event){
  if(event.target.files.length > 0){
    let src = URL.createObjectURL(event.target.files[0]);
    let preview = document.getElementById("file-ip-1-preview");
    preview.src = src;
    preview.style.display = "block";
  }
}
function myImgRemoveFunctionOne() {
  document.getElementById("file-ip-1-preview").src = "https://i.ibb.co/ZVFsg37/default.png";
}

</script>


<script>

    $(function() {
        setTimeout(function(){
            //startd=$("#fixture");
            $(document).find($("[id=fixture]")).each(function() {
               // alert($(".wish div").text());

                function addScore(score, $domElement) {

                    $("<span class='stars-container'>")
                        .addClass("stars-" + score.toString())
                        .text("★★★★★")
                        .appendTo($domElement);
                }
                var star = Math.round(parseInt($(this).text()));
                $(this).text('');

                addScore(star, $(this))
            });
        },100);

    });

</script>



<script>
var h=1;
        /*----------------------------------------
        Upload btn
        ------------------------------------------*/
        var SITE = SITE || {};

SITE.fileInputs = function() {
    var $this = $(this),
        $val = $this.val(),
        valArray = $val.split('\\'),
        newVal = valArray[valArray.length-1],
        $button = $this.siblings('.btn2'),
        $fakeFile = $this.siblings('.file-holder');
    if(newVal !== '') {
        $button.text('Photo Chosen');
        if($fakeFile.length === 0) {

            $('.newimg.img-path').val(newVal);
        } else {
            $fakeFile.text(newVal);
        }
    }
};


$('.file-wrapper input[type=file]').bind('change focus click', SITE.fileInputs);

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var tmppath = URL.createObjectURL(event.target.files[0]);

        reader.onload = function (e) {
            $('#img-uploaded').attr('src', e.target.result);

        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".uploader").change(function(){
    readURL(this);
});

    </script>

<script src="{{asset('/js/nice-select2.js')}}"></script>

<script>
    var options = {searchable: true};
    NiceSelect.bind(document.getElementById("country"), options);

    NiceSelect.bind(document.getElementById("city"), options);


function refcity(){
  var options={searchable:true};
  $('.city .nice-select').remove();
  NiceSelect.bind(document.getElementById("city"), options);


}
  
</script>
<script>


$( document ).ready(function(){
    if($('.toggle__input').is(":checked"))   
        $(".temp_hide_show").hide();

        else
        $(".temp_hide_show").show();
});
</script>
<script type="text/javascript">

    function valueChanged()
    {
        if($('.toggle__input').is(":checked"))   
        $(".temp_hide_show").hide();

        else
        $(".temp_hide_show").show();
    }
</script>

@endpush
