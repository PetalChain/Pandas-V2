<?php

namespace App\Services;

use App\Models\Discount;
use Illuminate\Support\Arr;

class SavedProductService
{
    public function add($id)
    {
        session()->put('saved_products', collect(session('saved_products'))
            ->prepend($id)
            ->unique()
            ->diff($this->filter())
            ->take(99)
            ->all());

        $this->persist();
    }

    public function get()
    {
        session()->put(
            key: 'saved_products',
            value: collect(session('saved_products'))
                ->when(auth()->check(), function ($views) {
                    return $views->concat(auth()->user()->savedProducts()
                        ->first(['items'])
                        ?->items ?? []);
                })
                ->unique()
                ->diff($this->filter())
                ->take(99)
                ->all()
        );

        $this->persist();

        return Discount::query()
            ->with('brand.media')
            ->find(session('saved_products'));
    }

    public function remove($ids)
    {
        session()->put('saved_products', collect(session('saved_products'))
            ->reject(fn ($productId) => in_array($productId, Arr::wrap($ids)))
            ->unique()
            ->diff($this->filter())
            ->take(99)
            ->all());

        $this->persist();
    }

    public function filter()
    {
        return Discount::query()
            ->onlyTrashed()
            ->orWhere('is_active', false)
            ->orWhereHas('brand', function ($query) {
                $query->onlyTrashed()
                    ->orWhere('is_active', false);
            })
            ->pluck('id');
    }

    public function persist()
    {
        if (auth()->check()) {
            auth()->user()->savedProducts()->update([
                'items' => session('saved_products'),
            ]);
        }
    }
}
