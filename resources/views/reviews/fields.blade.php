@extends('layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{__('lang.reviews')}}<small class="ml-3 mr-3">|</small><small>{{__('lang.pending_reviews')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('reviews.index') !!}">{{__('lang.reviews')}}</a>
          </li>
          <li class="breadcrumb-item active">{{__('lang.approve_edit')}}</li>
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
        @can('reviews.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('reviews.index') !!}"><i class="fa fa-list mr-2"></i>{{__('lang.review_table')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-pencil mr-2"></i>{{__('lang.review_edit')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::model($review, ['route' => ['approved.update', $review->id], 'method' => 'PUT']) !!}
      <div class="row">


<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">


          <!-- Vendor Name Field -->
          <div class="form-group row ">
            {!! Form::label('vendor_name', trans('lang.SP_name'), ['class' => 'col-4 control-label text-right', 'style' => 'text-align: left !important']) !!}
            <div class="col-8">
              {!! Form::text('vendor_name', $review->vendors->name,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'readonly']) !!}
            </div>
          </div>

          <!-- HomeOwner Name Field -->
          <div class="form-group row ">
            {!! Form::label('homeowner_name', trans('lang.reviewer'), ['class' => 'col-4 control-label text-right']) !!}
            <div class="col-8">
              {!! Form::text('homeowner_name', $review->clients->name,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder"), 'readonly']) !!}
            </div>
          </div>

          <!-- Description Field -->
          <div class="form-group row ">
            {!! Form::label('description', trans("lang.category_description"), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
              {!! Form::textarea('description', $review->description, ['class' => 'form-control','placeholder'=>
               trans("lang.category_description_placeholder")  ]) !!}
              <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
            </div>
          </div>
        </div>
        <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

        <!-- Rating Field -->

        <div class="form-group row">
        <div style="width:100%"><h3 style="text-align:center !important">Ratings<h3></div>

        </div><!--end div>-->

        <div class="form-group row">
            {!! Form::label('price_rating', trans('lang.Rating_Price'), ['class' => 'col-4 control-label text-right', 'style' => 'text-align: left !important']) !!}
            <div class="col-8">
              {!! Form::text('price_rating', $review->price_rating,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
            </div>
        </div><!--end div>-->

        <div class="form-group row">
            {!! Form::label('service_rating', trans('lang.Rating_Service'), ['class' => 'col-4 control-label text-right', 'style' => 'text-align: left !important']) !!}
            <div class="col-8">
              {!! Form::text('service_rating', $review->service_rating,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
            </div>
        </div><!--end div>-->

        <div class="form-group row">
            {!! Form::label('speed_rating', trans('lang.Rating_Speed'), ['class' => 'col-4 control-label text-right', 'style' => 'text-align: left !important']) !!}
            <div class="col-8">
              {!! Form::text('speed_rating', $review->speed_rating,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
            </div>
        </div><!--end div>-->

        <div class="form-group row">
            {!! Form::label('trust_rating', trans('lang.Rating_Trust'), ['class' => 'col-4 control-label text-right', 'style' => 'text-align: left !important']) !!}
            <div class="col-8">
              {!! Form::text('trust_rating', $review->trust_rating,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
            </div>
        </div><!--end div>-->

        <div class="form-group row">
            {!! Form::label('knowledge_rating', trans('lang.Rating_Knowledge'), ['class' => 'col-4 control-label text-right', 'style' => 'text-align: left !important']) !!}
            <div class="col-8">
              {!! Form::text('knowledge_rating', $review->knowledge_rating,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
            </div>
        </div><!--end div>-->
          
        </div>

      <!-- Submit Field -->
        <div class="form-group col-12 text-right">
          <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} </button>
          
          <a href="{!! route('reviews.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
        </div>


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
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
{{--dropzone--}}
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>
@endpush