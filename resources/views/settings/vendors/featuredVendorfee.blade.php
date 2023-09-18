
@extends('layouts.app')
@section('settings_title',trans('lang.user_table'))
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
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                <li class="nav-item">
                    <a class="nav-link " href="{!! route('vendors.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.vendor_table')}}</a>
                </li>
                <li class="nav-item">
                        <a class="nav-link " href="{!! route('vendors.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.vendor_create')}}</a>
                </li>
                <li class="nav-item">
                        <a class="nav-link active" href="{!! route('vendor.fee') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.featured_fee')}}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
        {!! Form::open(['route' => 'fee.save']) !!}
          <div class="form-group col-md-6 row">
              {!! Form::label('fee_amount', trans('lang.featured_fee'), ['class' => 'col-md-4 control-label']) !!}
              <div class="col-md-8">
              {!! Form::number('fee_amount', $count > 0 ? $value : null,  ['class' => 'form-control','placeholder'=>  'Insert amount','required']) !!} 

              <div class="form-group col-12 text-right" style="left:528px; top:35px">
                    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.user')}}</button>
                    <a href="{!! route('vendors.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>  
              </div>
          </div>
          {!! Form::close() !!}
          </div>
            <div class="clearfix"></div>
        </div>
    </div>
    </div>
    </div>
@endsection



