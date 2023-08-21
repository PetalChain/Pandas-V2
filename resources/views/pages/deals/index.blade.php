<?php
use function Laravel\Folio\{name};

name('deals.index');
?>
<x-layouts.app>
    <livewire:resources.deal-resource.pages.list-deals />
    {{-- <section class="px-[min(6.99vw,50px)] py-8 bg-black">
        <div class="divide-y divide-white text-white font-editorial text-3xl">
            <div class="text-center p-8">
                Top Category
            </div>
            @php
                $categories = \App\Models\Category::query()
                    ->isRoot()
                    ->inRandomOrder()
                    ->take(5)
                    ->get();
            @endphp
            @foreach ($categories as $category)
                <div class="text-center p-8">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>
    </section> --}}
    <x-list-recently-viewed />
    <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
        <div class="lg:flex lg:justify-between lg:gap-6">
            <h1 class="text-2xl lg:text-4xl text-center lg:text-start mb-8 lg:mb-0">
                Real people enjoying real perks
            </h1>
            <div class="lg:w-1/2">
                <div>Show us how Panda helps you live more life on your terms! Tag us @PandaPeople on Instagram and TikTok.</div>
            </div>
        </div>
        <div class="h-28"></div>
    </section>
    <livewire:resources.deal-resource.widgets.deal-listener />
</x-layouts.app>