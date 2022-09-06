<?php

namespace App\Models;

class BillInfo extends Model
{
    public function subject()
    {
        return $this->belongsTo(BillSubject::class, 'bill_subject_id', 'id')->select('id', 'type', 'name', 'cover')->withTrashed();
    }

    public function currency()
    {
        return $this->belongsTo(BillCurrency::class, 'current_bill_currency_id', 'id')->select('id', 'name')->withTrashed();
    }

    public function getCurrentAmountAttribute($value)
    {
        return bcdiv($value, 100, 2);
    }
}
