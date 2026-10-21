<div class="navbar bg-base-100">
    <div class="navbar-start">
        <div class="dropdown">
            <label tabindex="0" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('events.index') }}">Events</a></li>
                <li><a href="{{ route('about') }}">About</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                @auth
                    <li><a href="{{ route('volunteer.my') }}">Volunteer</a></li>
                @endauth
            </ul>
        </div>

        <a href="{{ route('home') }}" class="ml-4 normal-case text-xl">
            <x-ApplicationLogo class="block h-8 w-auto" :primary="true" />
        </a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('events.index') }}">Events</a></li>
            <li><a href="{{ route('about') }}">About</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
            @auth
                <li><a href="{{ route('volunteer.my') }}">Volunteer</a></li>
            @endauth
        </ul>
    </div>

    <div class="navbar-end">
        @auth
            @php $user = auth()->user(); @endphp
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost normal-case font-medium max-w-[10rem]">
                    <span class="truncate" style="font-size: 14px;">{{ $user->name }}</span>
                </label>
                <ul tabindex="0" class="menu dropdown-content p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li>
                        <a onclick="event.preventDefault(); document.getElementById('logout-form-{{ now()->timestamp }}').submit();">
                            Logout
                        </a>
                        <form id="logout-form-{{ now()->timestamp }}" method="POST" action="{{ route('logout') }}" style="display:none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <a class="btn" href="{{ route('login') }}">Log in</a>
        @endauth
    </div>
</div>
