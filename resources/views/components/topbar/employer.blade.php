@props([
    'forEmployer' => false,
])
<header class="relative z-10 bg-white border-b" x-data="{ mobileMenuOpened: false }">
    <div class="min-w-[100vw] bg-white mx-auto border-b fixed top-0">
        <nav class="mx-auto flex items-center justify-between px-[min(6.99vw,50px)] py-8 max-w-[1920px] " aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="/employer" class="-m-1.5 p-1.5">
                    <span class="sr-only">Panda People</span>
                    <x-logo with-text />
                    {{-- <img class="w-auto h-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt=""> --}}
                </a>
            </div>
            <div class="flex items-center gap-x-4 lg:hidden">
                @guest
                    <x-a :href="route('login', ['from' => 'employer'])">
                        <span class="text-xs md:text-base"><span class="hidden sm:inline">Member</span> Sign In</span>
                    </x-a>
                @endguest
                <x-a href="/employer/resources">
                    <span class="text-xs md:text-base">Schedule a Demo</span>
                </x-a>
                <button x-on:click="mobileMenuOpened = true" type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">Open main menu</span>
                    @svg('hamburger-menu', null, ['class' => 'w-6 h-6'])
                </button>
            </div>
            <div class="hidden lg:flex lg:gap-x-2">
                {{-- <div class="relative" x-data="{ open: false }">
                <button x-on:click="open = !open" type="button" class="flex items-center text-sm font-semibold leading-6 text-gray-900 gap-x-1" aria-expanded="false">
                    Product
                    <svg class="flex-none w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>

                <!--
            'Product' flyout menu, show/hide based on flyout menu state.

            Entering: "transition ease-out duration-200"
              From: "opacity-0 translate-y-1"
              To: "opacity-100 translate-y-0"
            Leaving: "transition ease-in duration-150"
              From: "opacity-100 translate-y-0"
              To: "opacity-0 translate-y-1"
          -->
                <div x-cloak x-show="open" x-transition x-on:click.away="open = false" class="absolute z-10 w-screen max-w-md mt-3 overflow-hidden bg-white shadow-lg -left-8 top-full rounded-3xl ring-1 ring-gray-900/5">
                    <div class="p-4">
                        <div class="relative flex items-center p-4 text-sm leading-6 rounded-lg group gap-x-6 hover:bg-gray-50">
                            <div class="flex items-center justify-center flex-none rounded-lg h-11 w-11 bg-gray-50 group-hover:bg-white">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900">
                                    Analytics
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Get a better understanding of your traffic</p>
                            </div>
                        </div>
                        <div class="relative flex items-center p-4 text-sm leading-6 rounded-lg group gap-x-6 hover:bg-gray-50">
                            <div class="flex items-center justify-center flex-none rounded-lg h-11 w-11 bg-gray-50 group-hover:bg-white">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" />
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900">
                                    Engagement
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Speak directly to your customers</p>
                            </div>
                        </div>
                        <div class="relative flex items-center p-4 text-sm leading-6 rounded-lg group gap-x-6 hover:bg-gray-50">
                            <div class="flex items-center justify-center flex-none rounded-lg h-11 w-11 bg-gray-50 group-hover:bg-white">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33" />
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900">
                                    Security
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Your customers’ data will be safe and secure</p>
                            </div>
                        </div>
                        <div class="relative flex items-center p-4 text-sm leading-6 rounded-lg group gap-x-6 hover:bg-gray-50">
                            <div class="flex items-center justify-center flex-none rounded-lg h-11 w-11 bg-gray-50 group-hover:bg-white">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900">
                                    Integrations
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Connect with third-party tools</p>
                            </div>
                        </div>
                        <div class="relative flex items-center p-4 text-sm leading-6 rounded-lg group gap-x-6 hover:bg-gray-50">
                            <div class="flex items-center justify-center flex-none rounded-lg h-11 w-11 bg-gray-50 group-hover:bg-white">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900">
                                    Automations
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Build strategic funnels that will convert</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-gray-900/5 bg-gray-50">
                        <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-100">
                            <svg class="flex-none w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M2 10a8 8 0 1116 0 8 8 0 01-16 0zm6.39-2.908a.75.75 0 01.766.027l3.5 2.25a.75.75 0 010 1.262l-3.5 2.25A.75.75 0 018 12.25v-4.5a.75.75 0 01.39-.658z"
                                    clip-rule="evenodd" />
                            </svg>
                            Watch demo
                        </a>
                        <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-100">
                            <svg class="flex-none w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z"
                                    clip-rule="evenodd" />
                            </svg>
                            Contact sales
                        </a>
                    </div>
                </div>
            </div> --}}
                <x-link href="/employer" :outlined="request()->is('employer')">Home</x-link>
                <x-link href="/employer/benefits" :outlined="request()->is('employer/benefits')">Benefits</x-link>
                <x-link href="/employer/resources" :outlined="request()->is('employer/resources')">Resources</x-link>
                <x-link href="/employer/company" :outlined="request()->is('employer/company')">Company</x-link>
            </div>
            <div class="hidden gap-3 lg:flex lg:flex-1 lg:justify-end">
                @guest
                    <x-link outlined class="p-4 border-transparent hover:border-black" :href="route('login', ['from' => 'employer'])">
                        <span class=""><span class="hidden sm:inline">Member</span> Sign In</span>
                    </x-link>
                @endguest
                <x-link outlined class="p-4 border-transparent hover:border-black" href="/employer/schedule-demo">
                    <span class="">Schedule a Demo</span>
                </x-link>
            </div>
        </nav>
    </div>
    <!-- Mobile menu, show/hide based on menu open state. -->
    <div x-show="mobileMenuOpened" x-transition x-cloak class="lg:hidden" role="dialog" aria-modal="true">
        <!-- Background backdrop, show/hide based on slide-over state. -->
        <div class="fixed inset-0 z-10"></div>
        <div class="fixed inset-y-0 right-0 z-10 w-full px-6 py-6 overflow-y-auto bg-white sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
            <div class="flex items-center justify-between">
                <x-a href="/employer" class="-m-1.5 p-1.5">
                    <span class="sr-only">Your Company</span>
                    <x-logo />
                </x-a>
                <button x-on:click="mobileMenuOpened = false" type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">Close menu</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flow-root mt-6">
                <div class="-my-6 divide-y divide-gray-500/10">
                    <div class="py-6 space-y-2">
                        {{-- <div class="-mx-3">
                            <button type="button" class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                                aria-controls="disclosure-1" aria-expanded="false">
                                Product
                                <!--
                    Expand/collapse icon, toggle classes based on menu open state.

                    Open: "rotate-180", Closed: ""
                  -->
                                <svg class="flex-none w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <!-- 'Product' sub-menu, show/hide based on menu state. -->
                            <div class="mt-2 space-y-2" id="disclosure-1">
                                <a href="#" class="block py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Analytics</a>
                                <a href="#" class="block py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Engagement</a>
                                <a href="#" class="block py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Security</a>
                                <a href="#" class="block py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Integrations</a>
                                <a href="#" class="block py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Automations</a>
                                <a href="#" class="block py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Watch demo</a>
                                <a href="#" class="block py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Contact sales</a>
                            </div>
                        </div> --}}
                        <x-a href="/employer" class="block px-3 py-2 -mx-3 text-2xl font-light leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Home</x-a>
                        <x-a href="/employer/benefits" class="block px-3 py-2 -mx-3 text-2xl font-light leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Benefits</x-a>
                        <x-a href="/employer/resources" class="block px-3 py-2 -mx-3 text-2xl font-light leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Resources</x-a>
                        <x-a href="/employer/company" class="block px-3 py-2 -mx-3 text-2xl font-light leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Company</x-a>
                    </div>
                    <div class="py-6">
                        @auth
                            <x-a href="/dashboard" class="block px-3 py-2 -mx-3 text-2xl font-light leading-7 text-gray-900 rounded-lg hover:bg-gray-50">My Account</x-a>
                        @endauth
                        @guest
                            <x-a href="/login?from=employer" class="block px-3 py-2 -mx-3 text-2xl font-light leading-7 text-gray-900 rounded-lg hover:bg-gray-50">Login</x-a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
