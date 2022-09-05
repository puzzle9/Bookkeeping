<?php

namespace App\Http\Controllers\Api\Bill\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BillPayee;

class Payee extends Controller
{
    public function list(Request $request, $bill_id)
    {
        $data = BillPayee::BillId($bill_id)->Sort()->select('name', 'remark')->get();
        return self::success($data);
    }

    public function createOrUpdate(Request $request, $bill_id)
    {
        $request->validate([
            'payee'          => [
                'required',
                'array',
            ],
            'payee.*.name'   => 'required',
            'payee.*.remark' => 'nullable|string|max:200',
        ]);

        BillPayee::BillId($bill_id)->whereNotIn('name', $request->input('payee.*.name'))->delete();

        foreach ($request->input('payee') as $sort => $payee) {
            BillPayee::withTrashed()->updateOrCreate([
                'book_account_id' => $bill_id,
                'name'            => $payee['name'],
            ], [
                'remark'     => $payee['remark'],
                'sort'       => $sort,
                'deleted_at' => null,
            ]);
        }

        return self::success();
    }
}
