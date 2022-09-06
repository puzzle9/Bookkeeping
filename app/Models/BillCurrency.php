<?php

namespace App\Models;

class BillCurrency extends Model
{
    public static function getUserBillDefaultCurrency($bill_id = null): int
    {
        return self::BillId($bill_id)->Sort()->value('id') ?: 0;
    }
}
