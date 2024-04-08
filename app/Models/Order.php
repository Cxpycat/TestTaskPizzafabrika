<?php

namespace App\Models;

use App\Kernel\Database\Database;

class Order extends Database
{
    protected static string $table = 'orders';
    protected array $fields = [
        'items',
        'status',
    ];

    //В ТЗ не было сказано как генерировать id, там видно лишь что это строка
    public static function createId(): string
    {
        $day = date('jndhi');

        return self::generateRandomLetter() . $day;
    }

    private static function generateRandomLetter(): string
    {
        $letters = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $randomIndex = mt_rand(0, strlen($letters) - 1);
        return $letters[$randomIndex];
    }

}