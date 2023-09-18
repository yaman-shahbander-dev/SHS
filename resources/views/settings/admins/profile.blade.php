@extends('layouts.app')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/nice-select2.css')}}">
    <style>
.center {
   display:inline;
   /* margin: 3px; */
 }

 .form-input {
   width:100px;
   padding:3px;
   background:#fff;
   border:2px dashed #427bff;
 }
 .form-input input {
   display:none;
 }
 .form-input label {
   display:block;
   /* width:100px; */
   height: auto;
   max-height: 100px;
   background:#333;
   border-radius:10px;
   cursor:pointer;
 }

 .form-input img {
   width:90px;
   height: 100px;
   margin: 2px;
   opacity: .4;
 }

 .imgRemove{
   position: relative;
   bottom: 114px;
   left: 68%;
   background-color: transparent;
   border: none;
   font-size: 30px;
   outline: none;
 }
 .imgRemove::after{
   content: ' \21BA';
   color: #fff;
   font-weight: 900;
   border-radius: 8px;
   cursor: pointer;
 }
 .small{
   color: firebrick;
 }


.content {
  padding: 10px;
}
#view {
  color: red;
  cursor: pointer;
}
.hidden-content {
  display: none;
}
.hidden-content .active {
  display: block;
}



 body {
            background-color: #eee
        }

        .card {
            background-color: #fff;
            border: none
        }

        .form-color {
            background-color: #fafafa
        }

        .form-control {
            height: 48px;
            border-radius: 25px
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #35b69f;
            outline: 0;
            box-shadow: none;
            text-indent: 10px
        }

        .c-badge {
            background-color: #35b69f;
            color: white;
            height: 20px;
            font-size: 11px;
            width: 92px;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2px
        }

        .comment-text {
            font-size: 13px
        }

        .wish {
            color: #35b69f;
            font-size: 16px;
        }

        .user-feed {
            font-size: 14px;
            margin-top: 12px
        }


        .stars-container {
            position: relative;
            display: inline-block;
            color: transparent;
            margin-left: 20px;
        }

        .stars-container:before {
            position: absolute;
            top: 0;
            left: 0;
            content: '★★★★★';
            color: lightgray;
        }

        .stars-container:after {
            position: absolute;
            top: 0;
            left: 0;
            content: '★★★★★';
            color: gold;
            overflow: hidden;
            height: 100%;

        }

        .custom-star-edit {
            position: relative;
            left: 65px;
            margin-bottom: 4px;

        }

        .space {
            margin-left: 15px;
        }


        .stars-0:after { width: 0%; }
        .stars-1:after { width: 1%; }
        .stars-2:after { width: 2%; }
        .stars-3:after { width: 3%; }
        .stars-4:after { width: 4%; }
        .stars-5:after { width: 5%; }
        .stars-6:after { width: 6%; }
        .stars-7:after { width: 7%; }
        .stars-8:after { width: 8%; }
        .stars-9:after { width: 9%; }
        .stars-10:after { width: 10%; }
        .stars-11:after { width: 11%; }
        .stars-12:after { width: 12%; }
        .stars-13:after { width: 13%; }
        .stars-14:after { width: 14%; }
        .stars-15:after { width: 15%; }
        .stars-16:after { width: 16%; }
        .stars-17:after { width: 17%; }
        .stars-18:after { width: 18%; }
        .stars-19:after { width: 19%; }
        .stars-20:after { width: 20%; }
        .stars-21:after { width: 21%; }
        .stars-22:after { width: 22%; }
        .stars-23:after { width: 23%; }
        .stars-24:after { width: 24%; }
        .stars-25:after { width: 25%; }
        .stars-26:after { width: 26%; }
        .stars-27:after { width: 27%; }
        .stars-28:after { width: 28%; }
        .stars-29:after { width: 29%; }
        .stars-30:after { width: 30%; }
        .stars-31:after { width: 31%; }
        .stars-32:after { width: 32%; }
        .stars-33:after { width: 33%; }
        .stars-34:after { width: 34%; }
        .stars-35:after { width: 35%; }
        .stars-36:after { width: 36%; }
        .stars-37:after { width: 37%; }
        .stars-38:after { width: 38%; }
        .stars-39:after { width: 39%; }
        .stars-40:after { width: 40%; }
        .stars-41:after { width: 41%; }
        .stars-42:after { width: 42%; }
        .stars-43:after { width: 43%; }
        .stars-44:after { width: 44%; }
        .stars-45:after { width: 45%; }
        .stars-46:after { width: 46%; }
        .stars-47:after { width: 47%; }
        .stars-48:after { width: 48%; }
        .stars-49:after { width: 49%; }
        .stars-50:after { width: 50%; }
        .stars-51:after { width: 51%; }
        .stars-52:after { width: 52%; }
        .stars-53:after { width: 53%; }
        .stars-54:after { width: 54%; }
        .stars-55:after { width: 55%; }
        .stars-56:after { width: 56%; }
        .stars-57:after { width: 57%; }
        .stars-58:after { width: 58%; }
        .stars-59:after { width: 59%; }
        .stars-60:after { width: 60%; }
        .stars-61:after { width: 61%; }
        .stars-62:after { width: 62%; }
        .stars-63:after { width: 63%; }
        .stars-64:after { width: 64%; }
        .stars-65:after { width: 65%; }
        .stars-66:after { width: 66%; }
        .stars-67:after { width: 67%; }
        .stars-68:after { width: 68%; }
        .stars-69:after { width: 69%; }
        .stars-70:after { width: 70%; }
        .stars-71:after { width: 71%; }
        .stars-72:after { width: 72%; }
        .stars-73:after { width: 73%; }
        .stars-74:after { width: 74%; }
        .stars-75:after { width: 75%; }
        .stars-76:after { width: 76%; }
        .stars-77:after { width: 77%; }
        .stars-78:after { width: 78%; }
        .stars-79:after { width: 79%; }
        .stars-80:after { width: 80%; }
        .stars-81:after { width: 81%; }
        .stars-82:after { width: 82%; }
        .stars-83:after { width: 83%; }
        .stars-84:after { width: 84%; }
        .stars-85:after { width: 85%; }
        .stars-86:after { width: 86%; }
        .stars-87:after { width: 87%; }
        .stars-88:after { width: 88%; }
        .stars-89:after { width: 89%; }
        .stars-90:after { width: 90%; }
        .stars-91:after { width: 91%; }
        .stars-92:after { width: 92%; }
        .stars-93:after { width: 93%; }
        .stars-94:after { width: 94%; }
        .stars-95:after { width: 95%; }
        .stars-96:after { width: 96%; }
        .stars-97:after { width: 97%; }
        .stars-98:after { width: 98%; }
        .stars-99:after { width: 99%; }
        .stars-100:after { width: 100; }
        .own-padding {
            padding-top:0px !important;
            width: 787px !important;
            padding-left: 0px !important;
            margin-left: 256px !important;
        }
        .box-profile .custom-star-edit {

            margin-left: -11px !important;
       }


  .center {
    display:inline;
    margin: 3px;
  }

  .form-input {
    width:100px;
    padding:3px;
    background:#fff;
    border:2px dashed #427bff;
  }
  .form-input input {
    display:none;
  }
  .form-input label {
    display:block;
    /* width:100px; */
    height: auto;
    max-height: 100px;
    background:#333;
    border-radius:10px;
    cursor:pointer;
  }

  .form-input img {
    width:90px;
    height: 100px;
    margin: 2px;
    opacity: .4;
  }

  .imgRemove{
    position: relative;
    bottom: 114px;
    left: 68%;
    background-color: transparent;
    border: none;
    font-size: 30px;
    outline: none;
  }
  .imgRemove::after{
    content: ' \21BA';
    color: #fff;
    font-weight: 900;
    border-radius: 8px;
    cursor: pointer;
  }
  .small{
    color: firebrick;
  }
    [type="file"] {
        height: 0;
        overflow: hidden;
        width: 0;
    }

    [type="file"] + label {
        background: #f15d22;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-family: 'Poppins', sans-serif;
        font-size: inherit;
        font-weight: 600;
        margin-bottom: 1rem;
        outline: none;
        padding: 1rem 50px;
        position: relative;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
        vertical-align: middle;
    }
    [type="file"] + label:hover {
        background-color: #d3460d;
    }
    [type="file"] + label.btn-2 {
        background-color: #54944c;

        overflow: hidden;
    }
    [type="file"] + label.btn-2::before {
        color: #fff;
        content: "\f382";
        font-family: "Font Awesome 5 Pro";
        font-size: 100%;
        height: 100%;
        right: 130%;
        line-height: 3.3;
        position: absolute;
        top: 0px;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
    }
    [type="file"] + label.btn-2:hover {
        background-color: #497f42;
    }
    [type="file"] + label.btn-2:hover::before {
        right: 75%;
    }


    /****** IGNORE ******/




    /****** CODE ******/

    .file-upload{display:block;text-align:center;font-family: Helvetica, Arial, sans-serif;font-size: 12px;}
    .file-upload .file-select{display:block;border: 2px solid #dce4ec;color: #34495e;cursor:pointer;height:40px;line-height:40px;text-align:left;background:#FFFFFF;overflow:hidden;position:relative;}
    .file-upload .file-select .file-select-button{background:#dce4ec;padding:0 10px;display:inline-block;height:40px;line-height:40px;}
    .file-upload .file-select .file-select-name{line-height:40px;display:inline-block;padding:0 10px;float: right;}
    .file-upload .file-select:hover{border-color:#962eaf;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
    .file-upload .file-select:hover .file-select-button{background:#962eaf;color:#FFFFFF;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
    .file-upload.active .file-select{border-color:#3fa46a;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
    .file-upload.active .file-select .file-select-button{background:#3fa46a;color:#FFFFFF;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
    .file-upload .file-select input[type=file]{z-index:100;cursor:pointer;position:absolute;height:100%;width:100%;top:0;left:0;opacity:0;filter:alpha(opacity=0);}
    .file-upload .file-select.file-select-disabled{opacity:0.65;}
    .file-upload .file-select.file-select-disabled:hover{cursor:default;display:block;border: 2px solid #dce4ec;color: #34495e;cursor:pointer;height:40px;line-height:40px;margin-top:5px;text-align:left;background:#FFFFFF;overflow:hidden;position:relative;}
    .file-upload .file-select.file-select-disabled:hover .file-select-button{background:#dce4ec;color:#666666;padding:0 10px;display:inline-block;height:40px;line-height:40px;}
    .file-upload .file-select.file-select-disabled:hover .file-select-name{line-height:40px;display:inline-block;padding:0 10px;}








    .box {
        background-color: #fff;
        border: 1px solid #ddd;
        display: block;
        max-width: 30em;

        border-radius: 4px;
    }
    .box .content {
        padding: 1em;
    }
    .btn2 {
        color: #fff;
        background-color: #007bff;
        border: 1px solid #007bff;
        text-align: center;
        display: inline-block;
        vertical-align: middle;
        white-space: nowrap;
        margin: 0.6em 0.6em .6em 0;
        padding: 0.35em .7em 0.4em;
        text-decoration: none;
        width: auto;
        position: relative;
        border-radius: 4px;
        user-select: none;
        outline: none;
        -webkit-transition: all, 0.25s, ease-in;
        -moz-transition: all, 0.25s, ease-in;
        transition: all, 0.25s, ease-in;
    }
    .btn2:hover, button:hover {
        background-color: #ddd;
        color: #333;
        -webkit-transition: all, 0.25s, ease-in;
        -moz-transition: all, 0.25s, ease-in;
        transition: all, 0.25s, ease-in;
    }
    .btn2:active, button:active {
        background-color: #ccc;
        box-shadow: 0 !important;
        top: 2px;
        -webkit-transition: background-color, 0.2s, linear;
        -moz-transition: background-color, 0.2s, linear;
        transition: background-color, 0.2s, linear;
        box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    }



    .newimg {
        border: 2px solid #eee;
        padding: 1em .25em;
        width: 96%;
        color: #999;
        border-radius: 4px;
        height: 45px;
    }

    .left, .right {
        display: table-cell;
        vertical-align: middle;
    }

    .left {
        width: 9em;
        min-width: 6em;
        padding-right: 1em;
    }
    .left .img2 {
        width: 88%;
    }

    .img-holder {
        display: block;
        vertical-align: middle;
        width: 2em;
        height: 2em;
    }
    .img-holder .img2 {
        width: 100%;
        max-width: 100%;
    }

    .file-wrapper {
        cursor: pointer;
        display: inline-block;
        overflow: hidden;
        position: relative;
    }
    .file-wrapper:hover .btn {
        background-color: #33adff !important;
    }

    .file-wrapper .newimg {
        cursor: pointer;
        font-size: 100px;
        height: 100%;
        filter: alpha(opacity=1);
        -moz-opacity: 0.01;
        opacity: 0.01;
        position: absolute;
        right: 0;
        top: 0;
        z-index: 9;
    }


</style>
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

                        <div class="card-body">
                            {!! Form::model($user, ['route' => ['adminsBoard.update', $user->id], 'method' => 'patch']) !!}
                            <div class="row">
                                @include('settings.admins.fields')
                            </div>
                            {!! Form::close() !!}
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
            <div class="card-body own-padding">
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
</script>

@endpush
