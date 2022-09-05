<?php

namespace App\Models;

class BillSubject extends Model
{
    const TYPE_ASSET       = 'asset';
    const TYPE_LIABILITIES = 'liabilities';
    const TYPE_INCOME      = 'income';
    const TYPE_EXPENSES    = 'expenses';
    const TYPE_EQUITY      = 'equity';

    const TYPES = [
        self::TYPE_ASSET       => '资产',
        self::TYPE_LIABILITIES => '负债',
        self::TYPE_INCOME      => '收入',
        self::TYPE_EXPENSES    => '支出',
        // self::TYPE_EQUITY      => '权益',
    ];
}
