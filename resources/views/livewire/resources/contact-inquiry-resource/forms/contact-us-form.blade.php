@props([
    'testimonial' => false,
])
<section>
    <div class="max-w-[1920px] mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div>
                @if ($testimonial)
                    <div class="relative min-h-[10rem] md:h-full w-full bg-cover" style="background-image: url({{ getMediaPath('assets/contact-us-banner.png') }})">
                        <div x-data="{
                            ...@js(['testimonials' => $this->testimonials]),
                            index: 0,
                        }" class="absolute inset-0 p-6 space-y-4">
                            <h3 class="text-4xl lg:text-6xl font-editorial" x-transition x-text="testimonials[index][1]"></h3>
                            <p x-text="testimonials[index][0]" x-transition>
                            </p>
                            <button x-on:click="
                                if (index - 1 > 0) {
                                    index--;
                                } else {
                                    index = testimonials.length - 1;
                                }">
                                @svg('arrow', 'h-12 rotate-180 -mx-3')
                            </button>
                            <button x-on:click="if (index + 1 >= testimonials.length) {
                                index = 0;
                            } else {
                                index++;
                            }">
                                @svg('arrow', 'h-12 -mx-3')
                            </button>
                        </div>
                    </div>
                @else
                    <div class="min-h-[10rem] md:h-full w-full bg-cover p-8 flex flex-col justify-between" style="background-image: url({{ getMediaPath('assets/contact-us-banner.png') }})">
                        <h1 class="text-6xl font-editorial lg:text-7xl 2xl:text-8xl">Both of our ears are open.</h1>
                        <p>
                            Questions? Suggestions? They’re all welcome.

                            We’re here to make your Panda Portal as helpful as possible and are always looking for ways to make daily living easier. Like a panda leisurely seeking out its roots, shoots, and leaves for lunch.

                            That said, we love prompt responses and won’t be leisurely about getting back to you.
                        </p>
                    </div>
                @endif
            </div>
            <div class="p-8 space-y-4 {{ $testimonial ? 'bg-panda-green' : '' }}">
                <h2 class="text-4xl font-light font-editorial">Contact Us</h2>
                <form wire:submit.prevent="create">
                    {{ $this->form }}
                    <div class="flex justify-end">
                        <x-button outlined class="inline-block mt-8 hover:border-transparent hover:bg-panda-green">
                            Send
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
