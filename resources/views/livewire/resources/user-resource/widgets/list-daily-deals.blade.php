<?php

use function Livewire\Volt\{computed};

$categories = computed(function () {
    return \App\Models\Category::query()
        ->take(7)
        ->get();
});

?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-panda-green">
    <div class="p-8">

    </div>
    <div class="p-8 space-y-4">
        <h2 class="text-3xl">
            We’re here to help you afford more of what brings you joy and everyday essentials.
        </h2>
        <p>
            Find everyday deals on:
        </p>
        <ul class="grid grid-cols-2">
            @foreach ($this->categories as $category)
                <li>
                    <x-link>{{ $category->name }}</x-link>
                </li>
            @endforeach
        </ul>
        <x-button outlined tag="a" href="/deals" class="inline-block">
            Discover more deals
        </x-button>
    </div>
</div>
