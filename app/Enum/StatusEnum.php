<?php

namespace App\Enum;

class StatusEnum implements Enum
{
    const AVAILABLE = 'available';
    const PENDING = 'pending';
    const SOLD = 'sold';

    public static function getList($id = null)
    {
        $list = [
            self::AVAILABLE => 'dostępny',
            self::PENDING => 'w oczekiwaniu',
            self::SOLD => 'sprzedany',
        ];

        if (!is_null($id)) {
            return isset($list[$id]) ? $list[$id] : null;
        }

        return $list;
    }
}
