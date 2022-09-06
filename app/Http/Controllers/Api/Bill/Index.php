<?php

namespace App\Http\Controllers\Api\Bill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BillCurrency;
use App\Models\BillPayee;
use App\Models\BillSubject;
use App\Models\BillList;
use App\Models\BillInfo;

use Illuminate\Support\Facades\DB;
use Termwind\Components\Dd;

class Index extends Controller
{
    public function base(Request $request, $bill_id)
    {
        $subject = BillSubject::BillId($bill_id)->select('id', 'bill_currency_id', 'parent_id', 'type', 'name', 'cover', 'remark')->get();

        $subject_format = function ($row) {
            return [
                'id'     => $row->id,
                'name'   => $row->name,
                'cover'  => $row->cover,
                'remark' => $row->remark,
                // 'currency_id'   => $row->bill_currency_id,
                // 'currency_name' => $row->currency->name,
            ];
        };

        $subject_datas = [];
        foreach ($subject->groupBy('type') as $type => $lists) {
            foreach ($lists->where('parent_id', 0) as $parent_info) {
                $info = $subject_format($parent_info);
                $info['children'] = $lists->where('parent_id', $parent_info->id)->map($subject_format)->values();
                $subject_datas[$type][] = $info;
            }
        }

        $subjects = [];
        foreach ($subject_datas as $subject_type => $subject_data) {
            $subjects[] = [
                'type'        => $subject_type,
                'type_string' => BillSubject::TYPES[$subject_type],
                'data'        => $subject_data,
            ];
        }

        $payees = BillPayee::BillId($bill_id)->Sort()->select('id', 'name', 'remark')->get();

        return self::success([
            'subjects' => $subjects,
            'payees'   => $payees,
        ]);
    }

    public function createOrUpdate(Request $request, $bill_id)
    {
        $request->validate([
            'id'              => 'nullable',
            'bill_payee_id'   => 'required',
            'remark'          => 'nullable|string|max:200',
            'time_occurrence' => 'required|date',

            'files' => 'array|max:6',

            'lists'                   => 'required|array|min:1',
            'lists.*.id'              => 'nullable',
            'lists.*.bill_subject_id' => 'required',
            'lists.*.current_amount'  => 'required',
            'lists.*.remark'          => 'nullable|string|max:200',
        ]);

        DB::beginTransaction();

        $bill_payee_id = $request->input('bill_payee_id');

        if (!is_numeric($bill_payee_id)) {
            $bill_payee_id = BillPayee::getPayeeIdByNameOrCreate($bill_payee_id, $bill_id);
        }

        $bill_list = BillList::updateOrCreate([
            'id' => $request->input('id'),
        ], [
            'book_account_id' => $bill_id,
            'bill_payee_id'   => $bill_payee_id,
            'time_occurrence' => $request->input('time_occurrence'),
            'remark'          => $request->input('remark'),
            'files'           => $request->input('files'),
        ]);

        $bill_list_id = $bill_list->id;

        BillInfo::where('bill_list_id', $bill_list_id)->whereNotIn('id', $request->input('lists.*.id'))->delete();

        $current_bill_currency_id = BillCurrency::getUserBillDefaultCurrency($bill_id);

        foreach ($request->input('lists') as $list) {
            BillInfo::updateOrCreate([
                'id'           => $list['id'] ?? 0,
                'bill_list_id' => $bill_list_id,
            ], [
                'current_bill_currency_id' => $current_bill_currency_id,
                'bill_subject_id'          => $list['bill_subject_id'],
                'current_amount'           => bcmul($list['current_amount'], 100),
                'remark'                   => $list['remark'],
            ]);
        }

        DB::commit();

        return self::success();
    }

    public function list(Request $request, $bill_id)
    {
        $db = BillList::BillId($bill_id)->select('id', 'bill_payee_id', 'time_occurrence', 'files', 'remark')->with('infos', 'payee')->latest('time_occurrence');

        if (!$request->filled('search')) {
            $db->take(50);
        }

        if ($request->filled('search.date')) {
            $db->whereDate('time_occurrence', $request->input('search.date'));
        }

        if ($request->filled('search.bill_payee_id')) {
            $db->where('bill_payee_id', $request->input('search.bill_payee_id'));
        }

        $data = $db->get();

        $formatData = $data->map(function ($row) {
            $amounts = [];
            $infos = $row->infos->map(function ($info) use (&$amounts) {
                $subject = $info->subject;
                $current_amount = $info->current_amount;
                if ($current_amount > 0) {
                    $amounts[] = $current_amount;
                }
                return [
                    'info_id'             => $info->id,
                    'current_amount'      => $current_amount,
                    'remark'              => $info->remark,
                    'subject_cover'       => $subject->cover ?? null,
                    'subject_name'        => $subject->name ?? null,
                    'subject_type_string' => BillSubject::TYPES[$subject->type] ?? null,
                    'currency'            => $info->currency->name ?? null,
                ];
            });

            return [
                'id'              => $row->id,
                'time_occurrence' => $row->time_occurrence,
                'payee'           => $row->payee->name ?? null,
                'files'           => $row->files,
                'remark'          => $row->remark,
                'amount'          => array_sum($amounts),
                'infos'           => $infos,
            ];
        });

        return self::success($formatData);
    }

    public function info(Request $request, $bill_id, $info_id)
    {
        $data = BillList::BillId($bill_id)->select('id', 'bill_payee_id', 'time_occurrence', 'files', 'remark')->with('infosSimple')->findOrFail($info_id);

        $original_files = json_decode($data->getRawOriginal('files'));
        $data->original_files = $original_files;

        return self::success($data);
    }

    public function delete(Request $request, $bill_id)
    {
        BillList::BillId($bill_id)->where('id', $request->input('id'))->delete();
        return self::success();
    }
}
