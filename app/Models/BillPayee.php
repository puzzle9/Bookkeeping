<?php

namespace App\Models;

class BillPayee extends Model
{
    public static function getPayeeIdByNameOrCreate($name, $bill_id)
    {
        return self::withTrashed()->updateOrCreate([
            'book_account_id' =>  $bill_id,
            'name' => $name,
        ], [
            'deleted_at' => null,
            'sort' => 0,
        ])->id;
    }
}
