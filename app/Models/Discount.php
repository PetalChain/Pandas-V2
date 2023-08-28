<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use App\Enums\DiscountCallToActionEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kirschbaum\PowerJoins\PowerJoins;

class Discount extends Model
{
    use HasFactory;
    use InteractsWithAuditable;
    use SoftDeletes;
    use PowerJoins;

    protected $fillable = [
        'name',
        'link',
        'api_link',
        'slug',
        'code',
        'cta',
        'uniqid',
        'description',
        'logo',
        'views',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'amount' => 'array',
        'starts_at' => 'immutable_datetime',
        'ends_at' => 'immutable_datetime',
        'is_active' => 'boolean',
        'cta' => DiscountCallToActionEnum::class,
    ];

    public function discountOffers()
    {
        return $this->hasMany(DiscountOffer::class);
    }

    public function discountTags()
    {
        return $this->hasMany(DiscountTag::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'discount_tags')
            ->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details')
            ->withTimestamps();
    }

    public function voucherType()
    {
        return $this->belongsTo(VoucherType::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function offerTypes()
    {
        return $this->belongsToMany(OfferType::class, 'discount_types')
            ->withTimestamps();
    }

    public function featuredDeals()
    {
        return $this->hasMany(FeaturedDeal::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function scopeForOrganization($query, $organization)
    {
        return $query->when($organization, function ($query, $organization) {
            $query->whereHas('brand', function ($query) use ($organization) {
                $query->forOrganization($organization);
            });
        });
    }

    protected function isAmountSingle(): Attribute
    {
        return Attribute::get(fn () => count($this->amount) == 1);
    }
}
