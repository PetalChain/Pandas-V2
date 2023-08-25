<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\BrandRegion;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountCategory;
use App\Models\DiscountRegion;
use App\Models\DiscountTag;
use App\Models\DiscountType;
use App\Models\FeaturedDeal;
use App\Models\Manager;
use App\Models\OfferType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Organization;
use App\Models\Region;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\VoucherType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Region::factory(10)->create();
        Organization::factory(10)->create()->each(function ($organization) {
            $user = User::factory()
                ->create([
                    'organization_id' => $organization->id,
                    'email' => 'manager' . $organization->getKey() . '@test.com',
                ]);
            $user->managers()->create(['organization_id' => $organization->getKey()]);
            $user->userPreference()->save(UserPreference::factory()->make());
        });
        User::factory(2)
            ->sequence(
                ['email' => 'admin1@test.com'],
                ['email' => 'admin2@test.com'],
            )
            ->admins()
            ->create()
            ->each(function ($user, $index) {
                $user->userPreference()->save(UserPreference::factory()->make());
            });

        $users = User::factory(50)->create()->each(function ($user) {
            $user->userPreference()->save(UserPreference::factory()->make());
        });

        collect([
            'Adidas', 'Nike', 'New Balance', 'LG', 'Panasonic',
            'Reebok', 'Apple', 'Polo', 'Ralph Laurens', 'Jerome',
        ])
            ->map(function ($brand) {
                return Brand::factory()->create(['name' => $brand]);
            });
        collect([
            'Apparel', 'Electronic', 'Sport', 'Food', 'Hardware',
            'Beauty', 'Skincare', 'Health', 'Property', 'Electricity',
        ])
            ->map(function ($category) {
                Category::factory()->create(['name' => $category]);
            });
        BrandCategory::factory(10)->create();
        BrandRegion::factory(10)->create();
        $offerTypes = OfferType::factory(10)->create();
        $voucherTypes = VoucherType::factory(10)->create();
        $discounts = Discount::factory(10)->create()->each(function ($discount) use ($offerTypes, $voucherTypes) {
            $discount->offerTypes()->attach($offerTypes->random());
            $discount->voucherType()->associate($voucherTypes->random());
            $discount->save();

            FeaturedDeal::query()->create(['discount_id' => $discount->getKey()]);
        });
        Tag::factory(10)->create();
        DiscountCategory::factory(10)->create();
        DiscountRegion::factory(10)->create();
        DiscountTag::factory(10)->create();
        Order::factory(125)
            ->create()
            ->each(function ($order) use ($users, $discounts) {
                foreach (range(1, 6) as $count) {
                    OrderDetail::factory()
                        ->for($discounts->random())
                        ->for($order)
                        ->create();
                }
            });

        DiscountType::factory(10)->create();
    }
}
