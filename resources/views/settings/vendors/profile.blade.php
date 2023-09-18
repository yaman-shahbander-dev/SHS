@extends('layouts.app')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->

    {{--dropzone--}}
    <link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
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

                    <!-- Profile Image -->
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-user mr-2"></i> {{trans('lang.user_about_me')}}</h3>
                        </div>
                        <div class="card-body box-profile" 
                                @if($user->background_profile != null || $user->background_profile != '') 
                                style = "background-image: 
                                  url('{{ asset('storage/vendors_background') . '/' . $user->background_profile }}');
                                background-repeat: no-repeat;
                                background-position: center;
                                background-size: cover;"
                                @else 
                                style = "background-image: 
                                  url('{{ asset('storage/vendors_background') . '/' . 'background.jpg' }}');
                                background-repeat: no-repeat;
                                background-position: center;
                                background-size: cover;"
                                @endif>
                            <div class="text-center">
                                <img src="{{$user->getFirstMediaUrl('avatar','icon')}}" class="profile-user-img img-fluid img-circle" alt="{{$user->name}}">
                            </div>
                            <h3 class="profile-username text-center">{{$user->name}}</h3>
                            <p id="fixture" class="text-muted custom-star-edit">
                            {{ $user->rating }}</p>
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
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-list mr-2"></i>{{trans('lang.working_hours')}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body ">
                                @foreach($user->days as $hour)
                                    <div class="row">
                                        <div class="col-md-3">
                                        <strong >{{ substr($hour->name_en,0,3)}}</strong>
                                        </div>
                                        <div class="col-md-5">

                                        <strong style="font-size: 13px;">{{ date("g:i A",strtotime($hour->pivot->start))}}</strong>
                                        </div>
                                        <div class="col-md-4">
                                        <strong style="font-size: 13px;">{{ date("g:i A",strtotime($hour->pivot->end)) }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- /.card-body -->
                        </div>
                    <!-- /.card -->

                    <!-- available-->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-list mr-2"></i>{{trans('lang.available_cities')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ">
                            <div class="row">
                            @foreach($user->vendor_city as $city)

                                    <div class="col-md-6" style="margin-bottom: 8px;">
                                        <strong>{{ $city->city_name}}</strong>
                                    </div>

                            @endforeach
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- social media-->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-list mr-2"></i>{{trans('lang.social_media')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ">
                            <div class="row">
                               <div class="col-md-12">
                               <strong><i>{{ trans('lang.facebook_link') }} :</i></strong>
                               </div>
                               <div class="col-md-12">
                               <a href={{ $user->facebook }}>{{ $user->facebook }}</a>
                               </div>
                               <div class="col-md-12">
                               <strong><i>{{ trans('lang.instagram_link') }} :</i></strong>
                               </div>
                               <div class="col-md-12">
                               <a href={{ $user->instagram }}>{{ $user->instagram }}</a>
                               </div>
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
                                        <a class="nav-link pt-1" href="{!! route('restaurants.create') !!}"><i class="fa fa-check-o"></i> {{trans('lang.app_setting_become_restaurant_owner')}}</a>
                                    </li>
                                </div>
                                @endhasrole
                            </ul>
                        </div>

                        <div class="card-body select_cities" data-route="{{url('api/user/select')}}">
                            {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}
                            <div class="row">
                                @include('settings.vendors.fields')
                            </div>
                            {!! Form::close() !!}
                            <div class="clearfix"></div>
                        </div>
                        <div class="card-body">
                            @include('settings.vendors.table')
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    </div>


                    <div class="row">
<div class="col-md-12">
        <!-- users who added this vendor as a favorite-->
        <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fa fa-list mr-2"></i> {{trans('lang.gallery')}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                      @foreach($SP_Galleries as $SP_Gallery)
                        <div class="col-md-4">
                          <a href="{{ asset('storage/gallery') . '/' . "$SP_Gallery->image" }}" download>
                           <img width="200px" height="100px" src="{{ asset('storage/gallery') . '/' . "$SP_Gallery->image" }}" alt="Image here!" style="
                            margin-bottom: 25px;
                            border: 1px solid #021a4054;
                            padding: 3px;
                            "> 
                          </a>
                          <bold
                          onclick="DeleteImageGallery('{{$SP_Gallery->id}}')" 
                          style="position:absolute; left: 85%; top: -8%;
                           color: white; z-index:1000; background-color: red; border-radius: 50%; width: 15px; height: 17px; cursor:pointer">
                            <strong style="position: absolute; top: -3px; left: 3px;">x</strong> 
                          </bold>
                        </div>
                      @endforeach
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        <!-- /.card -->
    </div>
</div>
           
<div class="row">
<div class="col-md-6">
        <!-- users who added this vendor as a favorite-->
        <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-list mr-2"></i> {{trans('lang.vendors')}}</h3>
                    <h6 style="font-size:11px"><i>{{trans('lang.users_who_added_SP_as_favorite')}} </i></h6>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                        <div class="row">
                    @foreach($favoriteVendor as $favorite)
                    <div class="col-md-12">
                        <strong>
                        <i class="fa fa-user mr-2"></i>{{ $favorite->name }} </strong>
                    </div>
                    @endforeach
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        <!-- /.card -->
</div>
                    
<div class="col-md-6">

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
</div>

<div class="row">
     <div class="col-md-12">
        <!-- vendor special-offers-->
        <div class="card">
            <div class="card-header" style="background-color: #e4e4e4">
                <h3 class="card-title"><i class="fa fa-list mr-2"></i>{{trans('lang.special_offers')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="background-color: #ececec">
                    <div class="row">
                @foreach($user->specialOffers as $specialOffer)
                        <div class="col-md-4" style="margin-bottom: 13px; ">
                            <strong><img style="border-radius:8px" width='100%' height='100px' src="{{ asset('storage/specialOffersPic/' . $specialOffer->image)  }}" alt="Image not found"></strong>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <h5><i><strong>{{ $specialOffer->title }}</strong></i></h5>
                            </div>
                            <div class="col-md-12">
                                <p style="color:#9a969e"><strong>{{ $specialOffer->description }}</strong></p>
                            </div>
                        </div>
                    
                @endforeach
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    <!-- /.card -->
    </div>
</div>
                <div class="card-body own-padding">
                    <div class="container mt-5 mb-5" style="margin-bottom:0px !important; margin-left: -262px !important;">
                        <div class="row height d-flex justify-content-center align-items-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="p-3">
                                        <h6>{{trans('lang.reviews')}} <span style="font-size: 12px;color: #777;">({{trans('lang.users_who_reviewd_me')}})</span></h6>
                                    </div>
                                    <div class="mt-2">

                                    @foreach($user->clients as $client)
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
                                    <span class="wish">Quality Rating</span>
                                    <div id="fixture">{{$client->pivot->service_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;Price Rating</span>
                                    <div id="fixture">{{$client->pivot->price_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;Speed Rating</span>
                                    <div id="fixture">{{$client->pivot->speed_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;Trust Rating</span>
                                    <div id="fixture">{{$client->pivot->trust_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;Knowledge Rating</span>
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


                <!-- Users I reviewd -->


                <div class="card-body own-padding">
                    <div class="container mt-5 mb-5"  style="margin-top: 0px !important; margin-left: -262px !important;">
                        <div class="row height d-flex justify-content-center align-items-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="p-3">
                                        <h6>{{trans('lang.reviews')}} <span style="font-size: 12px;color: #777;">({{trans('lang.service_providers_I_reviewd')}})</span></h6>
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
                                    <span class="wish">Quality Rating</span>
                                    <div id="fixture">{{$client->pivot->service_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;Price Rating</span>
                                    <div id="fixture">{{$client->pivot->price_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;Speed Rating</span>
                                    <div id="fixture">{{$client->pivot->speed_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;Trust Rating</span>
                                    <div id="fixture">{{$client->pivot->trust_rating}}</div>
                                 </div>
                                 <div class="space">
                                    <span class="wish">&nbsp;&nbsp;Knowledge Rating</span>
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
        </div>
    </section>
    @include('layouts.media_modal',['collection'=>null])
@endsection
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- select2 -->
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->

    {{--dropzone--}}
    <script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        var dropzoneFields = [];
    </script>
@endpush
@section('script')

<script>
    function DeleteImageGallery(id){
        if (confirm("Are you sure?") == true) {
			$.ajax({
                url: "{{route('DeleteGallerySpImage')}}",
                method: 'get',
                data: {
                    id : id
                },
                success: function() {
                    window.location.reload();
                }
            });
		} else {
			userPreference = "Save Canceled!";
		}
        // //
        

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

@endsection