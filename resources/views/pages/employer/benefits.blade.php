<?php

?>
@php
    $categories = \App\Models\Category::query()
        ->where('is_active', true)
        ->get();
@endphp
<x-layouts.app for-employer>
    <section class="mx-auto max-w-[1920px] bg-black">
        <div class="grid grid-cols-1 gap-2 lg:grid-cols-2">
            <div class="order-2 lg:order-1">
                <div class="-mb-24">
                    <img src="{{ asset('storage/assets/employer-benefits-featured.png') }}" />
                </div>
            </div>
            <div class="order-1 py-8 text-white lg:order-2 px-[min(6.99vw,50px)] lg:px-0 place-self-center">
                <h1 class="text-6xl lg:text-8xl 2xl:text-9xl font-editorial text-[#E0BAA5]">
                    Benefits that help employees go further
                </h1>
                <div class="max-w-lg mt-10 space-y-8">
                    <p class="">
                        Panda People’s voluntary benefits give your people a daily boost where it matters so they can make space to live, play, and party on their terms. Everyone’s got a unique style, even if they’re in a uniform. Reward their flair and see real culture emerge.
                    </p>
                    <x-link color="white" outlined href="#benefits">View benefits</x-link>
                </div>
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-[1920px]">
        <div class="grid grid-cols-1 gap-2 lg:grid-cols-2" x-data="{ activeAccordion: 0 }">
            @foreach (range(0, 5) as $i)
                <div class="mt-24" x-show="activeAccordion==({{ $i }})">
                    <img src="{{ asset('storage/assets/employer-benefit-program-' . $i . '.png') }}" />
                </div>
            @endforeach
            <div class="p-8">
                <h2 class="text-6xl lg:text-8xl 2xl:text-9xl font-editorial">
                    Benefits for Life Essentials
                </h2>
                <p>
                    Stop spending on pretend perks. Choose true benefits that let employees know their grit and work ethic is being noticed.
                </p>
                <ul class="divide-y">
                    @php
                        $items = [
                            'Childcare' => "<div>
                            <p>Parents mean business when it comes to childcare. So do we. Childcare costs have risen at twice the rate of inflation. This benefit helps parents worry less about finding affordable care for their kids so they can perform better at work.</p>
                            <ul>
                                <li>10% off local and nationally recognized child daycare and early education programs</li>
                                <li>Our concierge help employees find childcare in their area that fits their needs and budget</li>
                                <li>Expert assistance to help you develop on-site childcare programs for your employees</li>
                            </ul>
                        </div>
                        ",
                            'Housing Program' => "<ul>
                            <li>We enable employers to offer real housing assistance—discounted rent—to create a vast distinction between you and the next company.</li>
                            <li>Up to 20% off leases</li>
                        </ul>
                        ",
                            'Cell Phones' => "<div>
                            <p>Save your employees $700+ each year on cell phone plans and open a direct line of communication.</p>
                            <ul>
                                <li>Unlimited talk, text, data, and hotspot</li>
                                <li>Company-branded smartphones</li>
                                <li>Easy-to-use service lets you send notifications directly to employees</li>
                            </ul>
                        </div>
                        ",
                            'Health + Mental Wellness' => "<ul>
                            <li>Teleheath services give employees 24-hour access to primary care, therapy, and mental health services across the country</li>
                            <li>Affordable access to fitness, wellness, and gym memberships</li>
                            <li>Televet services connect employees to veterinary experts at any time, day or night</li>
                        </ul>",
                            'Financial Wellness' => 'We’ve partnered with multiple financial advisors to help employees climb out of debt and improve other aspects of financial wellness.',
                            'Grocery Program' => "<div>
                            <p>Grocery costs are among the most impacted commodities when inflation hits. Help your employees with a vital expense through exclusive savings.</p>
                            <ul>
                                <li>X% off groceries through a partnership with local and national supermarket chains</li>
                            </ul>
                        </div>",
                        ];
                    @endphp
                    @foreach ($items as $item => $content)
                        <li class="py-6">
                            <button class="text-2xl" x-on:click="activeAccordion = activeAccordion == @js($loop->index) ? null : @js($loop->index)">{{ $item }}</button>
                            <div class="grid grid-cols-2 gap-2 mt-6" x-show="activeAccordion == @js($loop->index)">
                                <div class="prose">{!! $content !!}</div>
                                <div class="text-center">
                                    <x-link outlined>Learn more</x-link>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
    <section class="px-[min(6.99vw,50px)] py-8 bg-neutral-300 grid min-h-[50vh]">
        <h5 class="place-self-center">Placeholder for video</h5>
    </section>
    <section class="px-[min(6.99vw,50px)] py-8">
        <x-hr />
        <div class="max-w-[1920px] mx-auto">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2" x-data="{ shown: false }" x-intersect.once="shown = true">
                    <h2 x-show="shown" x-transition.duration.2000 class="text-2xl md:text-5xl lg:text-7xl xl:text-8xl 2xl:text-9xl md:col-span-2 font-editorial">
                        …and Purchases Essential to Life
                    </h2>
                </div>
                <div class="space-y-6 lg:space-y-8 z-[1]">
                    <p class="lg:text-lg">
                        Panda People has partnered with retailers and entertainers to get your employees deep discounts on everything from sneakers to workwear to amusement park tickets.
                        <br /><br />
                        Up to 30% off
                    </p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-6 mt-48 lg:grid-cols-4">
            <div class="">
                <h4 class="text-3xl font-editorial">Meals & Entertainment</h4>
                <ul>
                    <li>Restaurants</li>
                    <li>Movies</li>
                    <li>Local attractions + sporting events</li>
                    <li>Amusement parks</li>
                </ul>
            </div>
            <div class="">
                <h4 class="text-3xl font-editorial">Apparel & Uniforms</h4>
                <ul>
                    <li>Dickies</li>
                    <li>Cherokee</li>
                    <li>Grey’s Anatomy</li>
                </ul>
            </div>
            <div class="">
                <h4 class="text-3xl font-editorial">Footwear</h4>
                <ul>
                    <li>Adidas</li>
                    <li>Skeetchers</li>
                    <li>Reebok</li>
                    <li>New Balance</li>
                </ul>
            </div>
            <div>
                <x-link href="employer/resources" outlined>Sschedule a demo</x-link>
            </div>
        </div>
        <x-hr />
    </section>
    <section class="w-full py-8 space-y-8 mx-auto max-w-[1920px] bg-black">
        <div class="text-white">
            <h4 class="text-6xl text-left lg:text-center lg:-ml-80 font-editorial lg:text-9xl">Attract. Retain.</h4>
            <h4 class="text-6xl text-right lg:text-center lg:ml-80 font-editorial lg:text-9xl">Grow. Perform.</h4>
        </div>
        <x-hr class="border-white" />
    </section>
</x-layouts.app>