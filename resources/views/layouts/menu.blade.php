@can('dashboard')
    <li class="nav-item" >
    @if(LaravelLocalization::getCurrentLocaleDirection() == "rtl")
        <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" style="float:right" href="{!! url('dashboard') !!}">
            @if($icons)
                <i class="nav-icon fa fa-dashboard"></i>
            @endif
            <p>@lang('lang.dashboard')</p>
        </a>
    @else
    <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}"  href="{!! url('dashboard') !!}">
            @if($icons)
                <i class="nav-icon fa fa-dashboard"></i>
            @endif
            <p>@lang('lang.dashboard')</p>
        </a>
    @endif
    </li>
@endcan

@can('users.index')
    <li class="nav-item has-treeview {{ Request::is('superAdminsBoard*') || Request::is('adminsBoard*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('superAdminsBoard*') || Request::is('adminsBoard*') ? 'active' : '' }}"> @if($icons)
            <i class="nav-icon fa fa-users"></i>@endif
            @if(LaravelLocalization::getCurrentLocaleDirection() == "rtl")
            <p>{{trans('lang.dashboard_admins')}} <i class="fa fa-angle-left"></i></p>
            @else
                <p>{{trans('lang.dashboard_admins')}} <i class="right fa fa-angle-left"></i></p>
            @endif
        </a>
        <ul class="nav nav-treeview">
            @can('showAdmin')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('adminsBoard*') ? 'active' : '' }}" href="{!! route('adminsBoard.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-user"></i>@endif<p>{{trans('lang.normal_admins')}}</p></a>
                </li>
            @endcan
            @can('superAdmin.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('superAdminsBoard*') ? 'active' : '' }}" href="{!! route('superAdminsBoard.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-user"></i>@endif<p>{{trans('lang.super_admins')}}</p></a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

    <!-- vendors -->
    @can('vendors.index')
    <li class="nav-item has-treeview {{ Request::is('vendors*') || Request::is('suggested/vendor*') || Request::is('unapprovedServiceProvider*')  ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('vendors*') || Request::is('suggested/vendor*') ? 'active' : '' }}"> @if($icons)<i class="nav-icon fa fa-users"></i>@endif
        @if(LaravelLocalization::getCurrentLocaleDirection() == "rtl")
            <p>{{trans('lang.vendors')}} <i class="fa fa-angle-left"></i></p>
        @else
            <p>{{trans('lang.vendors')}} <i class="right fa fa-angle-left"></i></p>
        @endif
        </a>
        <ul class="nav nav-treeview">
            @can('vendors.index')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('vendors*') ? 'active' : '' }}" href="{!! route('vendors.index') !!}">@if($icons)
                <i class="nav-icon fa fa-user"></i>@endif<p>{{trans('lang.vendor')}}</p></a>
            </li>
            @endcan
            @can('vendor.index')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('suggested/vendor*') ? 'active' : '' }}" href="{!! route('vendor.index') !!}">@if($icons)
                <i class="nav-icon fa fa-user"></i>@endif<p>{{trans('lang.suggested')}}</p></a>
            </li>
            @endcan

            @can('unapprovedServiceProvider.index')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('unapprovedServiceProvider*') ? 'active' : '' }}" href="{!! route('unapprovedServiceProvider.index') !!}">@if($icons)
                <i class="nav-icon fa fa-user"></i>@endif<p>{{trans('lang.unapproved_SP')}}</p></a>
            </li>
            @endcan

        </ul>
    </li>
    @endcan


    @can('users.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{!! route('users.index') !!}">@if($icons)
                <i class="nav-icon fa fa-users"></i>@endif<p>{{trans('lang.Home_Owner')}}</p></a>
    </li>
    @endcan

    @can('users.index')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('bannedUsers*') ? 'active' : '' }}" href="{!! route('bannedUsers.index') !!}">@if($icons)
                    <i class="nav-icon fa fa-ban"></i>@endif<p>{{trans('lang.banned_users')}}</p></a>
        </li>
    @endcan

 @can('delegate.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('sales_man*') ? 'active' : '' }}" href="{!! route('sales_man.index') !!}">@if($icons)
                    <i class="nav-icon fa fa-handshake-o"></i>@endif<p>{{trans('lang.delegates')}}</p></a>
                </li>
            @endcan

    @can('offers.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('special*') ? 'active' : '' }}" href="{!! route('offers.index') !!}">@if($icons)
        <i class="nav-icon fa fa-gift"></i>@endif<p>{{trans('lang.special_offers')}}</p></a>
    </li>
    @endcan

    @can('favorites.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('homeOwnerFavorites*') ? 'active' : '' }}" href="{!! route('homeOwnerFavorites.index') !!}">@if($icons)
        <i class="nav-icon fa fa-star"></i>@endif<p>{{trans('lang.favorites')}}</p></a>
    </li>
    @endcan

    @can('reviews.index')
    <li class="nav-item has-treeview {{ Request::is('reviews*') || Request::is('pending*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('reviews*') || Request::is('pending*') ? 'active' : '' }}"> @if($icons)
            <i class="nav-icon fa fa-comments"></i>@endif
            @if(LaravelLocalization::getCurrentLocaleDirection() == "rtl")
                <p>{{trans('lang.reviews')}} <i class="fa fa-angle-left"></i></p>
            @else
                <p>{{trans('lang.reviews')}}  <i class="right fa fa-angle-left"></i></p>
            @endif
        </a>
        <ul class="nav nav-treeview">
            @can('approved.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('reviews*') ? 'active' : '' }}" href="{!! route('approved.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-percent"></i>@endif<p>{{trans('lang.approved_reviews')}}</p></a>
                </li>
            @endcan
            @can('reviews.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('pending*') ? 'active' : '' }}" href="{!! route('reviews.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-percent"></i>@endif<p>{{trans('lang.pending_reviews')}}</p></a>
                </li>
            @endcan
        </ul>
    </li>
    @endcan


    @can('duration.index')
    <li class="nav-item has-treeview {{ Request::is('vendorRegistration*') || Request::is('durationOffer*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('vendorRegistration*') || Request::is('durationOffer*') ? 'active' : '' }}"> @if($icons)
                <i class="nav-icon fa fa-clock-o"></i>@endif
            <p>{{trans('lang.durations')}}<i class="right fa fa-angle-left"></i>
            </p>
        </a>

        <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('vendorRegistration*') ? 'active' : '' }}" href="{!! route('vendorRegistration.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-clock-o"></i>@endif<p>{{trans('lang.durations')}}</p></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('durationOffer*') ? 'active' : '' }}" href="{!! route('durationOffer.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-clock-o"></i>@endif
                        <p>{{trans('lang.duration_offers')}}</p></a>
                </li>
        </ul>
    </li>
    @endcan


<!-- @can('rating.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('rating*') ? 'active' : '' }}" href="{!! route('rating.index') !!}">@if($icons)
                <i class="nav-icon fa fa-star"></i>@endif<p>Ratings</p></a>
    </li>
@endcan -->


            <!-- @can('users.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{!! route('showAdmin') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>Admin</p></a>
                </li>
            @endcan -->







@can('balance.index')

        <li class="nav-item has-treeview {{ Request::is('balance*') || Request::is('transfer*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('transfer*') || Request::is('balance*') ? 'active' : '' }}"> @if($icons)
                <i class="nav-icon fa fa-bitcoin"></i>@endif
            <p>{{trans('lang.balances')}}<i class="right fa fa-angle-left"></i>
            </p>
        </a>


        <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('balance*') ? 'active' : '' }}" href="{!! route('balance.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-bitcoin"></i>@endif<p>{{trans('lang.balances')}}</p></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('transfer*') ? 'active' : '' }}" href="{!! route('transfer.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-exchange"></i>@endif
                        <p>{{trans('lang.transfer')}}</p></a>
                </li>
        </ul>
    </li>

@endcan

{{-- @can('chats.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('chats*') ? 'active' : '' }}" href="{{ route('chatify') }}">@if($icons)
                <i class="nav-icon fa fa-comment"></i>@endif<p>{{trans('chats')}}</p></a>
    </li>
@endcan--}}


<!-- @can('notifications.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('notifications*') ? 'active' : '' }}" href="{{ route('notification.index') }}">@if($icons)
                <i class="nav-icon fa fa-bell"></i>@endif<p>notifications</p></a>
    </li>
@endcan -->



    @can('categories.index')
    <li class="nav-item has-treeview {{ Request::is('categories*') || Request::is('subcategory*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('categories*') || Request::is('subcategory*') ? 'active' : '' }}"> @if($icons)
            <i class="nav-icon fa fa-tag"></i>@endif
            @if(LaravelLocalization::getCurrentLocaleDirection() == "rtl")
            <p>{{trans('lang.category_plural')}} <i class="fa fa-angle-left"></i></p>
            @else
                <p>{{trans('lang.category_plural')}} <i class="right fa fa-angle-left"></i></p>
            @endif
        </a>
        <ul class="nav nav-treeview">
            @can('categories.index')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}" href="{!! route('categories.index') !!}">@if($icons)
                <i class="nav-icon fa fa-tag"></i>@endif<p>{{trans('lang.category_plural')}}</p></a>
            </li>
            @endcan
            @can('subcategory.index')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('subcategory*') ? 'active' : '' }}" href="{!! route('subcategory.index') !!}">@if($icons)
                <i class="nav-icon fa fa-tag"></i>@endif<p>{{trans('lang.subcategory')}}</p></a>
            </li>
            @endcan
        </ul>
    @endcan
    </li>

@can('subscription.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('subscription*') ? 'active' : '' }}" href="{!! route('subscription.index') !!}">@if($icons)
                    <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.subscription')}}</p></a>
                </li>
            @endcan
@can('country.index')
    <li class="nav-item has-treeview {{ Request::is('country*') || Request::is('City*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('City*') || Request::is('country*') ? 'active' : '' }}"> @if($icons)
                <i class="nav-icon fa fa-flag"></i>@endif
            <p>{{trans('lang.country')}}<i class="right fa fa-angle-left"></i>
            </p>
        </a>

        <ul class="nav nav-treeview">
                @can('country.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('country*') ? 'active' : '' }}" href="{!! route('country.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-flag"></i>@endif<p>{{trans('lang.country')}}</p></a>
                </li>
                @endcan

                @can('city.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('City*') ? 'active' : '' }}" href="{!! route('city.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-building-o"></i>@endif
                        <p>{{trans('lang.city')}}</p></a>
                </li>
                @endcan
        </ul>
    </li>
@endcan

{{--@can('faqs.index')--}}
{{--    <li class="nav-item has-treeview {{ Request::is('faqCategories*') || Request::is('faqs*') ? 'menu-open' : '' }}">--}}
{{--        <a href="#" class="nav-link {{ Request::is('faqs*') || Request::is('faqCategories*') ? 'active' : '' }}"> @if($icons)--}}
{{--                <i class="nav-icon fa fa-support"></i>@endif--}}
{{--            <p>{{trans('lang.faq_plural')}} <i class="right fa fa-angle-left"></i>--}}
{{--            </p>--}}
{{--        </a>--}}
{{--        <ul class="nav nav-treeview">--}}
{{--            @can('faqCategories.index')--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ Request::is('faqCategories*') ? 'active' : '' }}" href="{!! route('faqCategories.index') !!}">@if($icons)--}}
{{--                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.faq_category_plural')}}</p></a>--}}
{{--                </li>--}}
{{--            @endcan--}}

{{--            @can('faqs.index')--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ Request::is('faqs*') ? 'active' : '' }}" href="{!! route('faqs.index') !!}">@if($icons)--}}
{{--                            <i class="nav-icon fa fa-question-circle"></i>@endif--}}
{{--                        <p>{{trans('lang.faq_plural')}}</p></a>--}}
{{--                </li>--}}
{{--            @endcan--}}
{{--        </ul>--}}
{{--    </li>--}}
{{--@endcan--}}

<!-- @can('languages.index')
<li class="nav-item">
    <a href="{!! url('languages') !!}" class="nav-link {{  Request::is('languages*') ? 'active' : '' }}">
        @if($icons)<i class="nav-icon fa fa-language"></i> @endif <p>{{trans('lang.app_setting_localisation')}}</p></a>
</li>
@endcan 


{{--@can('medias')--}}
{{--    <li class="nav-item">--}}
{{--        <a class="nav-link {{ Request::is('medias*') ? 'active' : '' }}" href="{!! url('medias') !!}">@if($icons)<i class="nav-icon fa fa-picture-o"></i>@endif--}}
{{--            <p>{{trans('lang.media_plural')}}</p></a>--}}
{{--    </li>--}}
{{--@endcan--}}


@can('app-settings')
    <!--<li class="nav-item has-treeview {{ Request::is('settings/mobile*') || Request::is('slides*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('settings/mobile*') || Request::is('slides*') ? 'active' : '' }}">
            @if($icons)<i class="nav-icon fa fa-mobile"></i>@endif
            <p>
                {{trans('lang.mobile_menu')}}
                <i class="right fa fa-angle-left"></i>
            </p></a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! url('settings/mobile/globals') !!}" class="nav-link {{  Request::is('settings/mobile/globals*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-cog"></i> @endif <p>{{trans('lang.app_setting_globals')}}
                        <span class="right badge badge-danger">New</span></p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/mobile/colors') !!}" class="nav-link {{  Request::is('settings/mobile/colors*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-pencil"></i> @endif <p>{{trans('lang.mobile_colors')}} <span class="right badge badge-danger">New</span>
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/mobile/home') !!}" class="nav-link {{  Request::is('settings/mobile/home*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-home"></i> @endif <p>{{trans('lang.mobile_home')}}
                        <span class="right badge badge-danger">New</span></p>
                </a>
            </li>

            @can('slides.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('slides*') ? 'active' : '' }}" href="{!! route('slides.index') !!}">@if($icons)<i class="nav-icon fa fa-magic"></i>@endif<p>{{trans('lang.slide_plural')}} <span class="right badge badge-danger">New</span></p></a>
                </li>
            @endcan
        </ul>

    </li>-->


    <li class="nav-item has-treeview {{
    Request::is('settings*')
        ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{
        Request::is('settings*')
          ? 'active' : '' }}"> @if($icons)<i class="nav-icon fa fa-cogs"></i>@endif
            <p>{{trans('lang.app_setting')}} <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <!--<li class="nav-item">
                <a href="{!! url('settings/app/globals') !!}" class="nav-link {{  Request::is('settings/app/globals*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-cog"></i> @endif <p>{{trans('lang.app_setting_globals')}}</p>
                </a>
            </li>-->

            <!-- @can('users.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{!! route('users.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-users"></i>@endif
                        <p>{{trans('lang.user_plural')}}</p></a>
                </li>
            @endcan -->
            @can('permissions.index')
                <li class="nav-item has-treeview {{ Request::is('settings/permissions*') || Request::is('settings/roles*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('settings/permissions*') || Request::is('settings/roles*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-user-secret"></i>@endif
                        <p>
                            {{trans('lang.permission_menu')}}
                            <i class="right fa fa-angle-left"></i>
                        </p></a>
                    <ul class="nav nav-treeview">
                    @can('permissions.create')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('settings/permissions') ? 'active' : '' }}" href="{!! route('permissions.index') !!}">
                                @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                <p>{{trans('lang.permission_table')}}</p>
                            </a>
                        </li>
                        @endcan
                            <!-- <li class="nav-item">
                                <a class="nav-link {{ Request::is('settings/permissions/create') ? 'active' : '' }}" href="{!! route('permissions.create') !!}">
                                    @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                    <p>{{trans('lang.permission_create')}}</p>
                                </a>
                            </li> -->

                        @can('roles.index')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('settings/roles') ? 'active' : '' }}" href="{!! route('roles.index') !!}">
                                    @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                    <p>{{trans('lang.role_table')}}</p>
                                </a>
                            </li>
                        @endcan
                        <!-- @can('roles.create')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('settings/roles/create') ? 'active' : '' }}" href="{!! route('roles.create') !!}">
                                    @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                    <p>{{trans('lang.role_create')}}</p>
                                </a>
                            </li>
                        @endcan -->
                    </ul>

                </li>
            @endcan

            <li class="nav-item">
                <a class="nav-link {{ Request::is('settings/customFields*') ? 'active' : '' }}" href="{!! route('customFields.index') !!}">@if($icons)
                        <i class="nav-icon fa fa-list"></i>@endif<p>{{trans('lang.custom_field_plural')}}</p></a>
            </li>

            <!--   Languages   -->
            <li class="nav-item">
                <a href="{!! url('settings/app/localisation') !!}" class="nav-link {{  Request::is('settings/app/localisation*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-language"></i> @endif <p>{{trans('lang.app_setting_localisation')}}</p></a>
            </li>
            <li class="nav-item">
                <a href="{!! url('settings/translation/en') !!}" class="nav-link {{ Request::is('settings/translation*') ? 'active' : '' }}">
                    @if($icons) <i class="nav-icon fa fa-language"></i> @endif <p>{{trans('lang.app_setting_translation')}}</p></a>
            </li>
           <!-- @can('currencies.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('settings/currencies*') ? 'active' : '' }}" href="{!! route('currencies.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-dollar"></i>@endif<p>{{trans('lang.currency_plural')}}</p></a>
                </li>
            @endcan -->

             <!-- <li class="nav-item">
                <a href="{!! url('settings/payment/payment') !!}" class="nav-link {{  Request::is('settings/payment*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-credit-card"></i> @endif <p>{{trans('lang.app_setting_payment')}}</p>
                </a>
            </li> -->

           <!-- <li class="nav-item">
                <a href="{!! url('settings/app/social') !!}" class="nav-link {{  Request::is('settings/app/social*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-globe"></i> @endif <p>{{trans('lang.app_setting_social')}}</p>
                </a>
            </li>-->

            <!--<li class="nav-item">
                <a href="{!! url('settings/app/notifications') !!}" class="nav-link {{  Request::is('settings/app/notifications*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-bell"></i> @endif <p>{{trans('lang.app_setting_notifications')}}</p>
                </a>
            </li>-->

            <!--<li class="nav-item">
                <a href="{!! url('settings/mail/smtp') !!}" class="nav-link {{ Request::is('settings/mail*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-envelope"></i> @endif <p>{{trans('lang.app_setting_mail')}}</p>
                </a>
            </li>-->

        </ul>
    </li>
@endcan
