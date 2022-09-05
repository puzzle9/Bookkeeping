<?php

namespace App\Models;

class BookAccount extends Model
{
    public static function userCanBill($user_id = null, $bill_id = null)
    {
        // todo: cache
        return self::UserId($user_id)->BillId($bill_id, 'id')->exists();
    }
}
