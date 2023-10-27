<footer x-data="{ shown: false }" x-intersect.once="shown = true">
    <div class="bg-black text-white">
        <div class="max-w-[1920px] mx-auto">
            <div x-show="shown" x-transition:enter.duration.1000ms.opactiy class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8">
                <x-a href="/">
                    <x-logo class="text-white" />
                </x-a>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <ul>
                        <li><x-a href="/">Home</x-a></li>
                        <li><x-a href="/benefits">Benefits</x-a></li>
                        <li><x-a href="/deals">Deals</x-a></li>
                        <li><x-a href="/help">Help</x-a></li>
                    </ul>
                    <ul>
                        @guest
                            <li><x-a href="/login">Sign in</x-a></li>
                        @endguest
                        @auth
                            <li><x-a href="/dashboard">My Account</x-a></li>
                        @endauth
                        <li><x-a href="/contact-us">Contact Us</x-a></li>
                        <li><x-a href="/employer">For Employers</x-a></li>
                    </ul>
                    <ul class="space-y-2">
                        <li><x-a href="/">@svg('fab-linkedin', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/benefits">@svg('fab-instagram', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/deals">@svg('fab-facebook', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/help">@svg('fab-youtube', 'w-5 h-5')</x-a></li>
                    </ul>
                </div>
                <livewire:resources.subscriber-resource.forms.create-subscriber-form />
            </div>
            <div class="border-t border-white p-8 flex gap-8 items-center">
                <h6 class="">Panda People® {{ date('Y') }} © All Rights Reserved</h6>
                <x-a href="#" class="">Privacy Policy</x-a>
            </div>
        </div>
    </div>
</footer>
