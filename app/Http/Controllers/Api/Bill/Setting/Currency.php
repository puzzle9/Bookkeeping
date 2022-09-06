<?php

namespace App\Http\Controllers\Api\Bill\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BillCurrency;

class Currency extends Controller
{
    public function list($bill_id)
    {
        $data = BillCurrency::BillId($bill_id)->Sort()->select('id', 'name')->get();
        return self::success($data);
    }

    public function createOrUpdate(Request $request, $bill_id)
    {
        // todo: 多币种记账
        $request->validate([
            'currency'   => [
                'required',
                'array',
                'min:1',
            ],
            'currency.*' => 'required',
        ]);

        $currencys = $request->input('currency');

        BillCurrency::BillId($bill_id)->whereNotIn('name', $currencys)->delete();

        foreach ($currencys as $sort => $currency) {
            BillCurrency::withTrashed()->updateOrCreate([
                'book_account_id' => $bill_id,
                'name'            => $currency,
            ], [
                'sort'       => $sort,
                'deleted_at' => null,
            ]);
        }

        return self::success();
    }
}
