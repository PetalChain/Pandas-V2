@props(['categories'])

<div class="divide-y">
    @foreach ($categories as $category)
        <div class="relative py-6 group">
            <h2 class="font-editorial text-5xl leading-[70px] text-center">{{ $category->name }}</h2>
            <x-a :href="route('deals.index', ['filter' => ['category_id' => $category->getKey()]])">
                <div class="invisible opacity-0 translate-y-5 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 duration-500 transition-transform ease-out absolute inset-y-0 z-[1] bg-black w-full">
                    <div class="relative flex items-center overflow-x-hidden h-full">
                        <div class="animate-marquee flex whitespace-nowrap items-center gap-4">
                            @foreach ($category->brands as $brand)
                                <img class="max-w-[150px] max-h-24 invert" src="{{ $brand->getFirstMediaUrl('logo') }}" alt="{{ $brand->name }} Logo">
                            @endforeach
                        </div>
                        <div class="h-full animate-marquee2 absolute top-0 flex whitespace-nowrap items-center gap-4 ml-4">
                            @foreach ($category->brands as $brand)
                                <img class="max-w-[150px] max-h-24 invert" src="{{ $brand->getFirstMediaUrl('logo') }}" alt="{{ $brand->name }} Logo">
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-a>
        </div>
    @endforeach
</div>

{{-- Use this as a backup --}}
{{-- <div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ getMediaPath('logo/adidas-white.png') }}" alt="Adidas Logo" class="max-w-[150px] max-h-[100px]">
        </div>
        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ getMediaPath('logo/boss.png') }}" alt="Hugo Boss Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ getMediaPath('logo/nb.png') }}" alt="NB Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ getMediaPath('logo/nike_white.png') }}" alt="Nike Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ getMediaPath('logo/puma.png') }}" alt="Puma Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ getMediaPath('logo/reebok.png') }}" alt="Reebok Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ getMediaPath('logo/skechers.png') }}" alt="Skechers Logo" class="max-w-[150px] max-h-[100px]">
        </div>
    </div>
</div> --}}
