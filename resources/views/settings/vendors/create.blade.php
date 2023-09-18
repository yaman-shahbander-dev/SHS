@extends('layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">

  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
  <!-- select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('flag.css')}}"/>
  <link rel="stylesheet" href="{{asset('/css/nice-select2.css')}}">
  <link href="{{asset('includes/css/style.css')}}" rel="stylesheet">
  <link href="{{asset('css/jquery.multiselect.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">

{{--<link rel="stylesheet" type="text/css" href="jquery.multiselect.css">--}}


  <style>


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




  /*//working houres*/

  /* RANGE SLIDER STYLES */
  .range-checkbox { clear:left; float:left; margin:13px 10px 10px; }
  .range-label { float:left; display:block; width:103px; margin:10px; cursor: pointer; }
  .range-slider { float:left; margin:10px; }
  .range-time { width:100px; float:left; margin:10px; }
  .range-day-disabled { opacity:.5; }
  .range-day .ui-slider-range { background: #00A000; }
  .range-day .ui-slider-handle { cursor:w-resize !important; }
  .range-day-disabled .ui-slider-range { background: #fff; }
  .range-day-disabled .ui-slider-handle { cursor:default !important; background:none !important; border:none !important; }
  .range-values { position:relative; display:block; height:20px; overflow:hidden; margin:10px 0 10px; }
  .range-values span { position: absolute; border-left: 1px solid grey; padding-left:5px }
  .range-values span.r-0 { left:0 }
  .range-values span.r-3 { left:12.5% }
  .range-values span.r-6 { left:25% }
  .range-values span.r-9 { left:37.5% }
  .range-values span.r-12 { left:50% }
  .range-values span.r-15 { left:62.5% }
  .range-values span.r-18 { left:75% }
  .range-values span.r-21 { left:87.5% }
  .range-values span.r-24 { left:100%;margin-left:-1px; }

  /* RESULT DATA STYLES */
  #schedule { width: 500px; background:#eee; margin-top:20px; }
  #schedule th { text-align: left;border-bottom:1px solid grey; }
  #schedule th,#schedule td { padding:5px; }

  /************ PARAMS ************/

  .range-slider,.range-values {
      width: 400px;
  }
  .range-values,#schedule,h1 {
      margin-left: 167px;
  }

  </style>
{{--  <link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">--}}
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.vendors')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.vendors_settings')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('vendors.index') !!}">{{trans('lang.vendors')}}</a>
          </li>
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
        <li class="nav-item">
          <a class="nav-link" href="{!! route('vendors.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.vendor_table')}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.vendor_create')}}</a>
        </li>
      </ul>
    </div>

    <div class="card-body" data-route="{{url('api/user/select')}}">
      {!! Form::open(['route' => 'vendors.store','enctype'=>"multipart/form-data"]) !!}

      <div class="row">
        @include('settings.vendors.fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@include('layouts.media_modal',['collection'=>null])
@endsection
@push('scripts_lib')
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{--<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>--}}
{{--dropzone--}}

<script src="{{asset('js/bootstrap.js')}}"></script>
<script src="{{asset('js/jquery.multiselect.js')}}"></script>
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

function saveAvatar() {

Path = function avatarSaver(event, inpClass = 'imgInp', button = 'btn2', inpPath = 'img-path') {
    var $avatar = $('#' + inpClass); //  avatar image
    if (event.target.id == inpClass) {
         $val = $avatar.val();
         valArray = $val.split('\\');
         newVal = valArray[valArray.length-1]; // only image name
         $button = $avatar.siblings('.' + button);
         $fakeFile = $avatar.siblings('.file-holder');

       if(newVal !== '') {
        $button.text('Photo Chosen');
        if($fakeFile.length === 0) { // If image is selected
            $('.' + inpPath).val(newVal); // input path
        } else {
            $fakeFile.text(newVal);
        }
      }
    }
};

$('.file-wrapper #imgInp').bind('change focus click', Path);

function readURL(input) {
        var reader = new FileReader();

        var tmppath = URL.createObjectURL(document.getElementById('imgInp').files[0]);

        reader.onload = function (e) {

            $('#img-uploaded').attr('src', e.target.result);

        }
        reader.readAsDataURL(document.getElementById('imgInp').files[0]);
}

$(".uploader").change(function(){
    readURL(this);
});

}


function savebackground() {

    Path = function backgroundSaver(event, inpClass = 'imgInput', button = 'background-btn', inpPath = 'path') {
    var $background = $('#' + inpClass); //  background image
    if (event.target.id == inpClass) {
         $val = $background.val();
         valArray = $val.split('\\');
         newVal = valArray[valArray.length-1]; // only image name
         $button = $background.siblings('.' + button);
         $fakeFile = $background.siblings('.file-holder');

       if(newVal !== '') {
        $button.text('Photo Chosen');
        if($fakeFile.length === 0) { // If image is selected
            $('.' + inpPath).val(newVal); // input path
        } else {
            $fakeFile.text(newVal);
        }
      }
    }
};

$('.file-wrapper #imgInput').bind('change focus click', Path);

function readURL(input) {
        var reader = new FileReader();

        var tmppath = URL.createObjectURL(document.getElementById('imgInput').files[0]);

        reader.onload = function (e) {

            $('.img-background').attr('src', e.target.result);

        }
        reader.readAsDataURL(document.getElementById('imgInput').files[0]);
}

$(".uploader").change(function(){
    readURL(this);
});

}


    </script>
<script src="{{asset('/js/nice-select2.js')}}"></script>

<script>
    var options = {searchable: true};
    NiceSelect.bind(document.getElementById("country"), options);
</script>

<script>
    var options = {searchable: true};
    NiceSelect.bind(document.getElementById("countries_code"), options);
</script>

{{--<script src="https://code.jquery.com/jquery-1.9.1.js"></script>--}}
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
    var rangeTimes = [];

    $(".range-slider").slider({
        range: true,
        min: 0,
        max: 1440,
        values: [340, 1080],
        step:30,
        slide: slideTime,
        change: updateOpeningHours
    });


    function slideTime(event, ui){
        if (event && event.target) {
            var $rangeslider = $(event.target);
            var $rangeday = $rangeslider.closest(".range-day");
            var rangeday_d = parseInt($rangeday.data('day'));
            var $rangecheck = $rangeday.find(":checkbox");
            var $rangetime = $rangeslider.next(".range-time");
        }

        if ($rangecheck.is(':checked')) {
            $rangeday.removeClass('range-day-disabled');
            $rangeslider.slider('enable');

            if (ui!==undefined) {
                var val0 = ui.values[0],
                    val1 = ui.values[1];
            } else {
                var val0 = $rangeslider.slider('values', 0),
                    val1 = $rangeslider.slider('values', 1);
            }

            var minutes0 = parseInt(val0 % 60, 10),
                hours0 = parseInt(val0 / 60 % 24, 10),
                minutes1 = parseInt(val1 % 60, 10),
                hours1 = parseInt(val1 / 60 % 24, 10);
            if (hours1==0) hours1=24;

            rangeTimes[rangeday_d] = [getTime(hours0, minutes0),getTime(hours1, minutes1)];

            $rangetime.text(rangeTimes[rangeday_d][0] + ' - ' + rangeTimes[rangeday_d][1]);

        } else {
            $rangeday.addClass('range-day-disabled');
            $rangeslider.slider('disable');

            rangeTimes[rangeday_d] = [];

            $rangetime.text('Closed');
        }
    }

    function updateOpeningHours() {
        if ($('#schedule').length) {
            $('#schedule tbody').empty();
        } else {
            $('#scheduleTable').append('\
      <table id="schedule">\
	    <thead>\
		    <tr>\
          <th>Day</th>\
			    <th>Start Time</th>\
			    <th>End Time</th>\
		    </tr>\
	    </thead>\
	    <tbody>\
	    </tbody>\
      </table>');
        }
        var cars = [];
        for (d=1; d<=7; d++) {
            rangeTimes[d][0]===undefined?console.log(d):cars.push({day_id:d,start:rangeTimes[d][0]+":00",end:rangeTimes[d][1]+":00"})  ;
            $('#schedule tbody').append('<tr>'+
                '<td>'+d+'</td>'+
                '<td>'+(rangeTimes[d][0]===undefined?'Closed':rangeTimes[d][0])+'</td>'+
                '<td>'+(rangeTimes[d][1]===undefined?'':rangeTimes[d][1])+'</td>'+
                '</tr>');
        }
        // var arr = [ name:"John", email:"Peter", sla:"Sally", lkj:"Jane" ];

        // cars.push({ "id": 1, brand: "Ferrari" });
        var myJSON = JSON.stringify(cars);
        document.getElementById("dayWorkingHours").value = myJSON;
        // console.log(arr[0]);
    }

    function getTime(hours, minutes) {
        var time = null;
        minutes = minutes + "";
        if (minutes.length == 1) {
            minutes = "0" + minutes;
        }
        return hours + ":" + minutes;
    }

    $('.range-checkbox').on('change', function(){
        var $rangecheck = $(this);
        var $rangeslider = $rangecheck.closest('.range-day').find('.range-slider');
        slideTime({target:$rangeslider});
        updateOpeningHours();
    });

    $("#scheduleSubmit").on('click', updateOpeningHours);



    slideTime({target:$('#range-slider-1')});
    slideTime({target:$('#range-slider-2')});
    slideTime({target:$('#range-slider-3')});
    slideTime({target:$('#range-slider-4')});
    slideTime({target:$('#range-slider-5')});
    slideTime({target:$('#range-slider-6')});
    slideTime({target:$('#range-slider-7')});
    updateOpeningHours();
</script>
@endpush

















