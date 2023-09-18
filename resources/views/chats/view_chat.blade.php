<style>
    .wrapper{
        width:100%;
    }
    .msger{
        margin:0 !important;
        width: 100% !important;
        max-width: 100% !important;
        border:none !important;
        box-shadow:none !important;
    }
    .card-body{
        padding:0 !important;
    }
    .msg-info-time {
        font-size: 0.70em !important;
    }
</style>
@extends('layouts.app')
<link rel="stylesheet" href="{{url('/dist/css/chat.css')}}">

<!--@can('suggestions.index')-->
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>

          <li class="breadcrumb-item active">Chat Info</li>
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
          <a class="nav-link " href="{{url('chats')}}"><i class="fa fa-list mr-2"></i>Chats List</a>
        </li>
        <li class="nav-item">
          <div class="nav-link " href="{!! url()->current() !!}">Restaurant : {{$restaurant_name}}</div>
        </li>
        <li class="nav-item">
          <div class="nav-link " href="{!! url()->current() !!}">Order ID : {{$order_id}}</div>
        </li>
        
      </ul>
    </div>
    <div class="card-body">
      @include('chats.messages')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection
<!--@endcan-->
