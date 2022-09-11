<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class BillList extends Model
{
    public function infos()
    {
        return $this->hasMany(BillInfo::class, 'bill_list_id', 'id')->select('id', 'bill_list_id', 'bill_subject_id', 'current_bill_currency_id', 'current_amount', 'transform_amount', 'remark')->with('subject', 'currency')->oldest('id');
    }

    public function infosSimple()
    {
        return $this->hasMany(BillInfo::class, 'bill_list_id', 'id')->select('id', 'bill_list_id', 'bill_subject_id', 'current_amount', 'remark')->oldest('id');
    }

    public function payee()
    {
        return $this->belongsTo(BillPayee::class, 'bill_payee_id', 'id')->select('id', 'name', 'remark')->withTrashed();
    }

    public function getFilesAttribute($value)
    {
        $files = [];
        foreach (json_decode($value, true) as $file) {
            $files[] = Storage::url($file);
        }
        return $files;
    }
}
