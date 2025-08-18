<?php
namespace App\Enums;

enum InsuranceType: string
{
    case TERM_LIFE = 'term_life';
    case WHOLE_LIFE = 'whole_life';
    case ENDOWMENT = 'endowment'; // 保障+貯蓄
    case VARIABLE_LIFE = 'variable_life'; // 運用成果で保障額変動
    case UNIVERSAL_LIFE = 'universal_life'; // 保障と貯蓄を柔軟に設定可能
    case ANNUITIES = 'annuities';
    case HEALTH_RIDER = 'health_rider';
    case CRITICAL_ILLNESS = 'critical_illness';
    case LONG_TERM_CARE = 'long_term_care';
    case WOMEN = 'women';
    case CANCER = 'cancer';
    case GROUP_LIFE = 'group_life';

    /**
     * 表示用ラベルを返す
     */
    public function label(): string
    {
        return match($this) {
            self::TERM_LIFE => '定期',
            self::WHOLE_LIFE => '終身',
            self::ENDOWMENT => '養老',
            self::VARIABLE_LIFE => '変額保険',
            self::UNIVERSAL_LIFE => 'ユニバーサル保険',
            self::ANNUITIES => '年金保険',
            self::HEALTH_RIDER => '医療特約',
            self::CRITICAL_ILLNESS => '重大疾病保険',
            self::LONG_TERM_CARE => '介護保険',
            self::WOMEN => '女性',
            self::CANCER => 'がん',
            self::GROUP_LIFE => '団体生命保険',
        };
    }

    /**
     * バリデーション用の値リストを返す
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
