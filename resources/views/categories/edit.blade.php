@extends('layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<!-- <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}"> -->
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">

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
        <h1 class="m-0 text-dark">{{trans('lang.category_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.category_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('categories.index') !!}">{{trans('lang.category_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.category_edit')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  @include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        @can('categories.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('categories.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.category_table')}}</a>
        </li>
        @endcan
        @can('categories.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('categories.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.category_create')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.category_edit')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::model($category, ['route' => ['categories.update', $category->id], 'method' => 'patch', 'enctype'=>"multipart/form-data"]) !!}
      <div class="row">
        @include('categories.fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@include('layouts.media_modal')
@endsection
@push('scripts_lib')
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script> -->
{{--dropzone--}}
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
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
@endpush