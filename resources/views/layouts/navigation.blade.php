<nav x-data="{ open: false }" class="bg-teal-700 border-b border-teal-600">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        @if (\App\Models\KuaSetting::logoUrl())
                            <img src="{{ \App\Models\KuaSetting::logoUrl() }}" alt="Logo {{ kua_setting('instansi', 'KUA') }}"
                                 class="h-9 w-9 rounded-md bg-white p-0.5 object-contain" />
                            <span class="hidden text-sm font-semibold text-white lg:block">{{ kua_setting('instansi', config('app.name')) }}</span>
                        @else
                            <x-application-logo class="block h-9 w-auto fill-current text-white" />
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('letters.index')" :active="request()->routeIs('letters.*')">
                        {{ __('Surat') }}
                    </x-nav-link>
                    <x-nav-link :href="route('submissions.index')" :active="request()->routeIs('submissions.*')">
                        {{ __('Permohonan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('letter-types.index')" :active="request()->routeIs('letter-types.*')">
                        {{ __('Jenis Surat') }}
                    </x-nav-link>
                    <x-nav-link :href="route('letter-templates.index')" :active="request()->routeIs('letter-templates.*')">
                        {{ __('Template') }}
                    </x-nav-link>
                    <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                        {{ __('Layanan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('announcements.index')" :active="request()->routeIs('announcements.*')">
                        {{ __('Pengumuman') }}
                    </x-nav-link>
                    <x-nav-link :href="route('kua-settings.edit')" :active="request()->routeIs('kua-settings.*')">
                        {{ __('Pengaturan') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-teal-100 bg-teal-700 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-teal-200 hover:text-white hover:bg-teal-600 focus:outline-none focus:bg-teal-600 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('letters.index')" :active="request()->routeIs('letters.*')">
                {{ __('Surat') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('submissions.index')" :active="request()->routeIs('submissions.*')">
                {{ __('Permohonan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('letter-types.index')" :active="request()->routeIs('letter-types.*')">
                {{ __('Jenis Surat') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('letter-templates.index')" :active="request()->routeIs('letter-templates.*')">
                {{ __('Template') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                {{ __('Layanan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('announcements.index')" :active="request()->routeIs('announcements.*')">
                {{ __('Pengumuman') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kua-settings.edit')" :active="request()->routeIs('kua-settings.*')">
                {{ __('Pengaturan') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-teal-600">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-teal-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
