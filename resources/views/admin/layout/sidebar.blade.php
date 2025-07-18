<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p> 
            </a>
        </li>
       
        @if(Auth::user()->type==1)
        <li class="nav-item {{ (request()->is('admin/admin-management*')) ? 'menu-open' : '' }}">
            <a href="{{route('admin.user_management.list.all')}}"
                class="nav-link {{ (request()->is('admin/admin-management*')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>Admin Management <i class="right fas fa-angle-left"></i></p>
            </a>
        </li>
        @endif


        @if(in_array('MASTER MODULES', $RolePass))
            <li class="nav-item {{ (request()->is('admin/master-module*')) ? 'menu-open' : '' }}">
                <a href="#"
                    class="nav-link {{ (request()->is('admin/master-module*')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-alt"></i>
                    <p>Master Modules <i class="right fas fa-angle-left"></i></p>
                </a>


                <ul class="nav nav-treeview">
                    @if(in_array('Blogs', $RolePass))
                        <li class="nav-item">
                            <a href="{{ route('admin.blog.list.all') }}"
                                class="nav-link {{ (request()->is('admin/master-module/blog*')) ? 'active active_nav_link' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Blogs</p>
                            </a>
                        </li>
                    @endif
                    @if(in_array('social_Media', $RolePass))
                    <li class="nav-item">
                        <a href="{{ route('admin.social_media.list.all') }}"
                            class="nav-link {{ (request()->is('admin/master-module/social-media*')) ? 'active active_nav_link' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Social Media</p>
                        </a>
                    </li>
                    @endif
                    @if(in_array('partners', $RolePass)) 
                    <li class="nav-item">
                        <a href="{{ route('admin.partners.list.all')}}" 
                           class="nav-link {{ (request()->is('admin/master-module/partners*')) ? 'active active_nav_link' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Partners</p>
                        </a>
                    </li>
                    @endif
                    @if(in_array('banner', $RolePass)) 
                    <li class="nav-item">
                        <a href="{{ route('admin.banner.list.all')}}" 
                        class="nav-link {{ (request()->is('admin/master-module/banner*')) ? 'active active_nav_link' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Banner Title</p>
                        </a>
                    </li>
                    @endif
                    @if(in_array('whychooseus', $RolePass))
                    <li class="nav-item">
                        <a href="{{ route('admin.whychooseus.list.all')}}"
                            class="nav-link {{ (request()->is('admin/master-module/whychooseus*')) ? 'active active_nav_link' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Why Choose Us</p>
                        </a>
                    </li>
                    @endif
                    
                    @if(in_array('offers', $RolePass))
                    <li class="nav-item">
                        <a href="{{ route('admin.offers.list.all')}}"
                            class="nav-link {{ (request()->is('admin/master-module/offers*')) ? 'active active_nav_link' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Offer List</p>
                        </a>
                    </li>
                    @endif

                    @if(in_array('support', $RolePass))
                    <li class="nav-item">
                        <a href="{{ route('admin.support.list.all')}}"
                            class="nav-link {{ (request()->is('admin/master-module/support*')) ? 'active active_nav_link' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Support</p>
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a href="{{ route('admin.tag.list.all')}}"
                            class="nav-link {{ (request()->is('admin/master-module/tags*')) ? 'active active_nav_link' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Tags List</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.newsletter.list.all') }}"
                        class="nav-link {{ (request()->is('admin/newsletter/index*')) ? 'active active_nav_link' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>News Letter</p>
                        </a>
                    </li>
                   
                </ul>
            </li>
        @endif

        {{-- <pre>{{ print_r($RolePass, true) }}</pre> --}}
            {{-- {{dd($RolePass)}} --}}
        @if(in_array('ITINERARIES', $RolePass))
            <li class="nav-item {{ (request()->is('admin/itineraries*')) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (request()->is('admin/itineraries*')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-alt"></i>
                    <p> Itinerary Management <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.itineraries.list.all') }}"
                        class="nav-link {{ (request()->is('admin/itineraries/itinerary*')) ? 'active active_nav_link' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Itinerary List </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.destination.list.all')}}"
                            class="nav-link {{ (request()->is('admin/itineraries/destinations*')) ? 'active active_nav_link' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Destinations</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.tripcategory.list.all')}}"
                            class="nav-link {{ (request()->is('admin/itineraries/tripcategory*')) ? 'active active_nav_link' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Trip Category</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.packageCategory.list.all')}}"
                            class="nav-link {{ (request()->is('admin/itineraries/package-category*')) ? 'active active_nav_link' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Package Category</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.assignCitytoPackage.index')}}"
                            class="nav-link {{ (request()->is('admin/itineraries/packages-from-top-cities*')) ? 'active active_nav_link' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Packages from Top Cities</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.popularpackages.list.all') }}"
                        class="nav-link {{ (request()->is('admin/itineraries/popular-packages*')) ? 'active active_nav_link' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Popular Packages</p>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        
        @if(in_array('WEBSITE SETTINGS', $RolePass))
        <li class="nav-item {{ (request()->is('admin/settings*')) ? 'menu-open' : '' }}">
            <a href="{{route('admin.settings')}}"
                class="nav-link {{ (request()->is('admin/settings*')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>Website Settings <i class="right fas fa-angle-left"></i></p>
            </a>
        </li>
        @endif
        @if(Auth::user()->type==1)
        <li class="nav-item {{ (request()->is('admin/article*')) ? 'menu-open' : '' }}">
            <a href="{{route('admin.article.list.all')}}"
                class="nav-link {{ (request()->is('admin/article*')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>Article Management <i class="right fas fa-angle-left"></i></p>
            </a>
        </li>
        @endif


        @if(in_array('CONTENT MANAGEMENT', $RolePass))
        <li class="nav-item {{ (request()->is('admin/content*')) ? 'menu-open' : '' }}">
            <a href="#"
                class="nav-link {{ (request()->is('admin/content*')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>Content Management <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
                @if(in_array('Page content', $RolePass))
                <li class="nav-item">
                    <a href="{{route('admin.page_content.list.all')}}"
                        class="nav-link {{ (request()->is('admin/content/page-content*')) ? 'active active_nav_link' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Page Content</p>
                    </a>
                </li>
                @endif  
            </ul>
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="javascript:void(0)"
                onclick="event.preventDefault();document.getElementById('logout-form').submit()">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                Logout
            </a>
        </li>
    </ul>
</nav>
