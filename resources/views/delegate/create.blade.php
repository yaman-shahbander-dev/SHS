@extends('layouts.settings.default')
@push('css_lib')
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
  <!-- select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
  
@endpush
@section('settings_title',trans('lang.delegate_table'))
@section('settings_content')
@include('flash::message')
@include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link" href="{!! route('sales_man.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.delegate_table')}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.delegate_create')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body" data-route="{{url('api/user/select')}}">
      {!! Form::open(['route' => 'sales_man.store']) !!}
      <div class="row">
        @include('delegate.fields')
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
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>

</script>
{{--  for select--}}

@endpush