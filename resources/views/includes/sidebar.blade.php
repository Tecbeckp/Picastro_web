<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
               <img src="{{asset('assets/images/picastro.png')}}" alt="Hair Transplant" height="36">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets/images/picastro.png')}}" alt="Hair Transplant" height="35">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{route('dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{asset('assets/images/picastro.png')}}" alt="Hair Transplant" height="36">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets/images/picastro.png')}}" alt="Hair Transplant" height="36">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">


            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('dashboard')}}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                   
                </li> <!-- end Dashboard Menu -->
                
                
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-services">Menu</span></li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('users.index')}}">
                        <i class="ri-user-fill"></i> <span data-key="t-users">Users</span>
                    </a>
                   
                </li> <!-- end Dashboard Menu -->
                
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('posts.index')}}">
                        <i class=" ri-image-fill "></i> <span data-key="t-users">Posts</span>
                    </a>
                   
                </li> <!-- end Dashboard Menu -->
                
                
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('starcamps.index')}}">
                        <i class="ri-pages-line"></i> <span data-key="t-starcamps">StarCamps</span>
                    </a>
                   
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPages">
                        <i class="ri-file-list-3-line"></i> <span data-key="t-pages">Content Pages</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarPages">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('privacyPolicy')}}" class="nav-link" data-key="t-privacy-policy">Privacy Policy</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('termsConditions')}}" class="nav-link" data-key="t-term-conditions">Terms and Conditions</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('security')}}" class="nav-link" data-key="t-search-results"> Security </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('help')}}" class="nav-link" data-key="t-term-conditions">Help</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('faq')}}" class="nav-link" data-key="t-term-conditions">FAQ's</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('about-us')}}" class="nav-link" data-key="t-term-conditions">About Us</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>