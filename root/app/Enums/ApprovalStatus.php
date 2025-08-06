<?php
namespace App\Enums;

enum ApprovalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    /**
     * 日本語ラベルを返す
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => '申請待ち',
            self::APPROVED => '承認済',
            self::REJECTED => '却下',
        };
    }

    /**
     * バリデーション用の値リストを返す
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * セレクトボックス用（value => label）
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
