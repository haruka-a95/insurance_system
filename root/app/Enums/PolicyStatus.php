<?php
namespace App\Enums;

enum PolicyStatus: string
{
    case ACTIVE = '契約中';
    case CANCELLED = '解約';
    case LAPSED = '失効';

    /**
     * バリデーション用の値リストを返す
     */
    public static function values():array
    {
        return array_column(self::cases(), 'value');
    }
}