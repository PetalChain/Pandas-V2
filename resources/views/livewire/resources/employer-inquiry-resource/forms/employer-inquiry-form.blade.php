<section>
    <div class="max-w-[1920px] mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div>
                <div class="relative min-h-[10rem] md:h-full w-full bg-cover" style="background-image: url({{ getMediaPath('assets/contact-us-banner.png') }})">
                    <div class="absolute inset-0 p-6 space-y-4">
                        <h3 class="text-6xl lg:text-7xl xl:text-8xl 2xl:text-9xl font-editorial" x-transition>The Benefits of tomorrow</h3>
                        <p x-transition>
                            Schedule a demo with an expert in Panda’s employee benefits packages and reward your workforce with perks that reduce their cost of living at less cost to <u>you</u>.

                            The best part? These perks better retention and give you the edge in the competitive talent marketplace.
                        </p>
                    </div>
                </div>
            </div>
            <div class="p-8 space-y-4">
                <h2 class="text-6xl font-light font-editorial">Schedule a<br /> demo with Panda</h2>
                <form wire:submit.prevent="create">
                    {{ $this->form }}
                    <div class="flex justify-start">
                        <x-button outlined class="inline-block mt-8 hover:bg-panda-green hover:border-transparent">
                            Send
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
