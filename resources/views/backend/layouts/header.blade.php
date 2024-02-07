<div class="content-header">
    <!-- Left Section -->
    <div class="d-flex align-items-center">
        <!-- Toggle Sidebar -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
        <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
            <i class="fa fa-fw fa-bars"></i>
        </button>
        <!-- END Toggle Sidebar -->

        <!-- Toggle Mini Sidebar -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
        <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
            <i class="fa fa-fw fa-ellipsis-v"></i>
        </button>
        <!-- END Toggle Mini Sidebar -->
    </div>
    <!-- END Left Section -->

    <!-- Right Section -->
    <div class="d-flex align-items-center">
        <!-- User Dropdown -->
        @if(!auth()->user())
            <span><a href="{{url('login')}}">Login<i class="fa fa-fw fa-sign-in-alt mr-1" ></i></a>
            <a href="{{url('register')}}">Register<i class="fa fa-fw fa-plus mr-1" ></i></a>
            <a href="{{ url('/auth/google') }}">Google Login<i class="fab fa-fw fa-google mr-1" ></i></a></span>
        @endif
        @if(auth()->user())
        <div class="dropdown d-inline-block ml-2">
            <button type="button" class="btn btn-sm btn-dual d-flex align-items-center" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle" src="{{ asset('backend/media/avatars/avatar10.jpg') }}" alt="Header Avatar" style="width: 21px;">
                <span class="d-none d-sm-inline-block ml-2">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0" aria-labelledby="page-header-user-dropdown">
                <div class="p-3 text-center bg-primary-dark rounded-top">
                    <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('backend/media/avatars/avatar10.jpg') }}" alt="">
                    <p class="mt-2 mb-0 text-white font-w500">{{ \Illuminate\Support\Facades\Auth::user()->name }}</p>
                    @if(\Illuminate\Support\Facades\Auth::user()->role == \Illuminate\Support\Facades\Config::get('variable_constants.role.admin'))
                        <p class="mb-0 text-white-50 font-size-sm">Admin</p>
                    @else
                        <p class="mb-0 text-white-50 font-size-sm">Customer</p>
                    @endif
                </div>
                <div class="p-2">
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{url('user/profile') }}">
                        <span class="font-size-sm font-w500">My Profile</span>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{url('change_password')}}">
                        <span class="font-size-sm font-w500">Change Password</span>
                    </a>
                    <div role="separator" class="dropdown-divider"></div>
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ url('logout') }}">
                        <span class="font-size-sm font-w500">Log Out</span>
                    </a>
                </div>
            </div>
        </div>
        @endif
        <!-- END User Dropdown -->
    </div>
    <!-- END Right Section -->
</div>
