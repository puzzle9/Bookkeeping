<?php

namespace App\Http\Controllers\Api\Bill\Setting;

use App\Http\Controllers\Controller;
use App\Models\BillSubject;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

class Subject extends Controller
{
    public function parent(Request $request, $bill_id)
    {
        $data = BillSubject::BillId($bill_id)->where('type', $request->input('type'))->where('parent_id', 0)->select('id', 'name')->get();
        return self::success($data);
    }

    public function list(Request $request, $bill_id)
    {
        $data = BillSubject::BillId($bill_id)->whereIn('type', $request->input('types'))->select('id', 'parent_id', 'name', 'type', 'bill_currency_id', 'remark', 'cover', 'balance')->get();

        $datas = [];
        foreach ($data->groupBy('type') as $type => $lists) {
            foreach ($lists->where('parent_id', 0) as $info) {
                $info->children = $lists->where('parent_id', $info->id)->values();
                $datas[$type][] = $info;
            }
        }

        return self::success($datas);
    }

    public function createOrUpdate(Request $request, $bill_id)
    {
        $data = $request->validate([
            'name' => 'required',
            'type' => [
                'required',
                Rule::in(array_keys(BillSubject::TYPES)),
            ],

            'bill_currency_id' => 'required',
            'parent_id'        => 'required',
            'remark'           => 'nullable|string|max:200',
        ]);

        $id = $request->input('id');
        $parent_id = $request->input('parent_id');

        if ($parent_id && $id == $parent_id) {
            self::failed('不能将自己的分组点设为自己');
        }

        if ($parent_id && BillSubject::BillId($bill_id)->where('id', $parent_id)->doesntExist()) {
            self::failed('分组ID错误');
        }

        BillSubject::updateOrCreate([
            'id' => $id,
        ], [
            'book_account_id' => $bill_id,
            ...$data,
        ]);

        if ($id && $parent_id) {
            BillSubject::BillId($bill_id)->where('parent_id', $id)->update([
                'parent_id' => $parent_id,
            ]);
        }

        return self::success();
    }

    public function delete(Request $request, $bill_id)
    {
        $request->validate([
            'id'      => 'required',
            // todo: 移动账单
            'move_id' => 'nullable',
        ]);

        $id = $request->input('id');

        if (BillSubject::BillId($bill_id)->where('parent_id', $id)->exists()) {
            self::failed('存在子节点 暂不能删除');
        }

        BillSubject::BillId($bill_id)->where('id', $id)->delete();

        return self::success();
    }
}
