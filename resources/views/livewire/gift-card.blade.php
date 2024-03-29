<div>
    @foreach ($orderQueue->gifts ?? [] as $downloadKey => $gift)
    @php($discount = App\Models\Discount::firstWhere('code', $gift['contentProviderCode']))
    @php($copyContent = !empty($gift['pin']) ? substr($gift['cardNumber'], 0, -1 * strlen($gift['pin'])) : $gift['cardNumber'])
    <div style="--cols-default: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] gap-4 mb-5">
        <li class="fi-in-repeatable-item block">
            <dl>
                <div style="--cols-default: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] fi-in-component-ctn gap-6">
                    <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
                        <section x-data="{
        isCollapsed: false,
    }" class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" id="apiCalls.0.target-egift-frc" data-has-alpine-state="true">
                            <header class="fi-section-header flex items-center gap-x-3 overflow-hidden px-6 py-4">
                                <img class="fi-section-header-icon self-start fi-color-gray text-gray-400 dark:text-gray-500 h-6 w-6" src="https://panda-static.s3.us-east-2.amazonaws.com/assets/panda_logo.png">
                                <div class="grid flex-1 gap-y-1">
                                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                                        {{$discount?->name}}
                                    </h3>
                                </div>
                                <div class="download-button-container" x-show="$wire.showDownload">
                                    <button wire:loading.remove wire:click="downloadGiftCard('{{ $downloadKey }}')" class="hover:underline" id="generate_pdf">Download</button>
                                    <span wire:loading>Downloading...</span>
                                </div>
                            </header>
                            <div class="fi-section-content-ctn border-t border-gray-200 dark:border-white/10">
                                <div class="fi-section-content p-6">
                                    <dl>
                                        <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(2, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-in-component-ctn gap-6">
                                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                                <div class="fi-in-entry-wrp">
                                                    <div class="grid gap-y-2">
                                                        <div class="grid gap-y-2">
                                                            <dd class="">
                                                                <div class="fi-in-image flex items-center gap-x-2.5">
                                                                    <div class="flex gap-x-1.5">
                                                                        <img src="{{$discount->media?->first()?->original_url}}" style="height: {{$size==='sm' ? 170 : 200}}px;" class="max-w-none object-cover object-center ring-white dark:ring-gray-900">
                                                                    </div>
                                                                </div>
                                                            </dd>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                                <div>
                                                    <dl>
                                                        <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(2, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-in-component-ctn gap-{{$size==='sm' ? 2 : 6}}">
                                                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                                                <div class="fi-in-entry-wrp">
                                                                    <div class="grid">
                                                                        <div class="flex items-center justify-between gap-x-3">
                                                                            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">
                                                                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                                                    Amount
                                                                                </span>
                                                                            </dt>
                                                                        </div>
                                                                        <div class="grid">
                                                                            <dd class="">
                                                                                <div class="fi-in-text">
                                                                                    <div class="fi-in-affixes flex">
                                                                                        <div class="min-w-0 flex-1">
                                                                                            <div class="">
                                                                                                <div>
                                                                                                    <div class="fi-in-text-item inline-flex items-center gap-1.5 text-sm leading-6 text-gray-950 dark:text-white  " style="">
                                                                                                        <div class="">
                                                                                                            $ {{ $gift['amount']}}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </dd>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @isset($gift['pin'])

                                                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                                                <div class="fi-in-entry-wrp">

                                                                    <div class="grid">
                                                                        <div class="flex items-center justify-between gap-x-3">
                                                                            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">


                                                                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                                                    Access Number
                                                                                </span>

                                                                            </dt>
                                                                        </div>

                                                                        <div class="grid">
                                                                            <dd class="">
                                                                                <div class="fi-in-text">
                                                                                    <div class="fi-in-affixes flex">

                                                                                        <div class="min-w-0 flex-1">
                                                                                            <div class="">
                                                                                                <div>
                                                                                                    <div class="cursor-pointer fi-in-text-item inline-flex items-center gap-1.5 text-sm leading-6 text-gray-950 dark:text-white">

                                                                                                        <div onclick="copyToClipboard('{{ $gift['pin'] }}')" class="cursor-pointer flex items-center">
                                                                                                            <span>{{ $gift['pin']}} </span>
                                                                                                            <svg xmlns=" http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 ml-2">
                                                                                                                <path stroke-linecap=" round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z" />
                                                                                                            </svg>
                                                                                                        </div>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </dd>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endisset
                                                            <div style=" --col-span-default: 1 / -1;" class="col-[--col-span-default]">
                                                                <div class="fi-in-entry-wrp">
                                                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                                        Card Number
                                                                    </span>

                                                                    <div class="grid gap-y-2">

                                                                        <div class="grid gap-y-2">
                                                                            <dd class="">
                                                                                <div class="fi-in-text">
                                                                                    <div class="fi-in-affixes flex">

                                                                                        <div class="min-w-0 flex-1">
                                                                                            <div class="">
                                                                                                <div>
                                                                                                    <div class="fi-in-text-item inline-flex items-center gap-1.5 text-sm leading-6 text-gray-950 dark:text-white  " style="">

                                                                                                        <div onclick="copyToClipboard('{{ $copyContent }}')" class="cursor-pointer flex items-center">
                                                                                                            <span># {{ $copyContent }}</span>
                                                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 ml-2">
                                                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z" />
                                                                                                            </svg>
                                                                                                        </div>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </dd>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                                                <div class="fi-in-entry-wrp">

                                                                    <div class="grid gap-y-2">
                                                                        <div class="flex items-center justify-between gap-x-3">
                                                                            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">

                                                                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                                                    Scan code
                                                                                </span>

                                                                            </dt>
                                                                            <!--[if ENDBLOCK]><![endif]-->

                                                                            <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                                                        </div>
                                                                        <!--[if ENDBLOCK]><![endif]-->

                                                                        <div class="grid gap-y-2">
                                                                            <dd class="">
                                                                                <!--[if BLOCK]><![endif]-->
                                                                                <div class="fi-in-image flex items-center gap-x-2.5">
                                                                                    <!--[if BLOCK]><![endif]-->
                                                                                    <div class="flex gap-x-1.5">
                                                                                        <!--[if BLOCK]><![endif]--> <img src="{{ barCodeGenerator($gift['cardNumber'])}}" style="height: 25px;" class="max-w-none object-cover object-center ring-white dark:ring-gray-900">
                                                                                        <!--[if ENDBLOCK]><![endif]-->

                                                                                        <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                                                                    </div>

                                                                                    <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                                                                    <!--[if ENDBLOCK]><![endif]-->
                                                                                </div>
                                                                                <!--[if ENDBLOCK]><![endif]-->
                                                                            </dd>

                                                                            <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--[if ENDBLOCK]><![endif]-->
                                                        </div>
                                                    </dl>

                                                </div>
                                            </div>
                                            <!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!--[if ENDBLOCK]><![endif]-->
                </div>
            </dl>

        </li>
        <!--[if ENDBLOCK]><![endif]-->
    </div>
    @endforeach
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Optional: Display a brief confirmation message
            alert('Copied Sucessfully!');
        }).catch(err => {
            console.error('Error copying to clipboard: ', err);
        });
    }
</script>