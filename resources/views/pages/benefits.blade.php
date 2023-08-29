<?php
use function Laravel\Folio\{name};

name('benefits');
?>
@php
    $categories = \App\Models\Category::query()
        ->where('is_active', true)
        ->get();
@endphp
<x-layouts.app>
    <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
        <h1 class="text-6xl max-w-xl">Get Ready to go beyond the basic</h1>
    </section>
    <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h1 class="text-6xl max-w-xl">
                    Benefits for Life Essentials
                </h1>
                <p>
                    At Panda People, we believe in providing you with the types of benefits that enhance life and make work more rewarding.

                    We’ve partnered with trusted vendors to offer services that ease daily stresses, like finding quality daycare for your kids, paying your cellphone bill, and getting health, wellness, and vet services
                    that fit your needs and wallet.
                </p>
            </div>
            <div class="space-y-6">
                <h3 class="text-xl">Here's more what we offers:</h3>
                <ul x-data="{ activeAccordion: null }" class="divide-y">
                    @foreach (['Up to 20% off your rent' => '', 'Cellphone programs' => ''] as $item => $content)
                        <li class="py-6">
                            <button class="text-2xl" x-on:click="activeAccordion = activeAccordion == @js($loop->index) ? null : @js($loop->index)">{{ $item }}</button>
                            <div class="mt-6" x-show="activeAccordion == @js($loop->index)">
                                <p>We know rent is likely your greatest expense, so we’ve done something about it. By working with select landlords, we are helping people like you lower your rent by up to 20%.</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="flex flex-col lg:flex-row gap-6">
                    <p class="lg:w-1/2">Learn how to can sign up for supplemental benefits your employer can offer through Panda People. </p>
                    <div class="lg:w-1/2">
                        <x-link href="#" class="hover:bg-panda-green mx-auto" outlined>See Additional Benefits</x-link>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto bg-panda-green">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-panda-green">
            <div class="pt-[100%] bg-cover bg-center" style="background-image: url({{ asset('storage/assets/list-daily-deals.png') }})">
            </div>
            <div class="p-8 space-y-4 place-self-center">
                <h2 class="text-3xl">
                    We’re here to help you afford more of what brings you joy and everyday essentials.
                </h2>
                <h5 class="text-xl">
                    Find everyday deals on:
                </h5>
                <ul class="grid grid-cols-2">
                    @foreach ($categories as $category)
                        <li>
                            <x-a :href="route('deals.index', ['filter' => ['category_id' => $category->getKey()]])">{{ $category->name }}</x-a>
                        </li>
                    @endforeach
                </ul>
                <x-link outlined :href="route('deals.index')">
                    Discover more deals
                </x-link>
            </div>
        </div>
    </section>
</x-layouts.app>
