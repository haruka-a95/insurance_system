<?php
namespace App\Enums;

enum PolicyStatus: string
{
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';

    /**
     * 表示用ラベル
     */
    public function label(): string
    {
        return match($this) {
            self::ACTIVE => '契約中',
            self::CANCELLED => '解約',
            self::EXPIRED => '失効',
        };
    }

    /**
     * バリデーション用の値リストを返す
     */
    public static function values():array
    {
        return array_column(self::cases(), 'value');
    }
}