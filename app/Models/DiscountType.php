<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
    use HasFactory;

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function offerType()
    {
        return $this->belongsTo(OfferType::class);
    }
}
