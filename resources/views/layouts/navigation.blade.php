<nav x-data="{ open: false }" class="border-b border-cyan-100 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center space-x-8">
                <a href="{{ route('events.index') }}" class="text-xl font-bold text-cyan-600">
                    {{ config('app.name', 'Cyan') }}
                </a>

                <div class="hidden space-x-6 sm:flex">
                    <a href="{{ route('events.index') }}"
                       class="text-sm font-medium {{ request()->routeIs('events.index', 'events.show') ? 'text-cyan-700 underline underline-offset-8' : 'text-gray-600 hover:text-cyan-700' }}">
                        Events
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-cyan-700 underline underline-offset-8' : 'text-gray-600 hover:text-cyan-700' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('events.create') }}"
                           class="text-sm font-medium {{ request()->routeIs('events.create', 'events.edit') ? 'text-cyan-700 underline underline-offset-8' : 'text-gray-600 hover:text-cyan-700' }}">
                            New Event
                        </a>
                        <a href="{{ route('categories.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('categories.*') ? 'text-cyan-700 underline underline-offset-8' : 'text-gray-600 hover:text-cyan-700' }}">
                            Categories
                        </a>
                        <a href="{{ route('registrations.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('registrations.*') ? 'text-cyan-700 underline underline-offset-8' : 'text-gray-600 hover:text-cyan-700' }}">
                            My Registrations
                        </a>
                    @endauth
                </div>
            </div>

            <div class="hidden items-center space-x-4 sm:flex">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-cyan-700">
                        {{ Auth::user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                            Log out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-cyan-700">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-cyan-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-cyan-700">
                        Register
                    </a>
                @endauth
            </div>

            <button @click="open = ! open" class="rounded-md p-2 text-gray-500 hover:bg-gray-100 sm:hidden" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-cyan-100 sm:hidden">
        <div class="space-y-1 px-4 py-3 text-sm">
            <a class="block py-1 text-gray-700" href="{{ route('events.index') }}">Events</a>
            @auth
                <a class="block py-1 text-gray-700" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="block py-1 text-gray-700" href="{{ route('events.create') }}">New Event</a>
                <a class="block py-1 text-gray-700" href="{{ route('categories.index') }}">Categories</a>
                <a class="block py-1 text-gray-700" href="{{ route('registrations.index') }}">My Registrations</a>
                <a class="block py-1 text-gray-700" href="{{ route('profile.edit') }}">Profile ({{ Auth::user()->name }})</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block py-1 text-left text-gray-700">Log out</button>
                </form>
            @else
                <a class="block py-1 text-gray-700" href="{{ route('login') }}">Log in</a>
                <a class="block py-1 text-gray-700" href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </div>
</nav>
