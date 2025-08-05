<?php
namespace App\Enums;

enum InsuranceType: string
{
    case TERM_LIFE = '定期';
    case WHOLE_LIFE = '終身';
    case ENDOWMENT = '養老';//保障+貯蓄
    case VARIABLE_LIFE = '変額保険';//運用成果で保障額変動
    case UNIVERSAL_LIFE = 'ユニバーサル保険';//保障と貯蓄を柔軟に設定可能
    case ANNUITIES = '年金保険';
    case HEALTH_RIDER = '医療特約';
    case CRITICAL_ILLNESS = '重大疾病保険';
    case LONG_TERM_CARE = '介護保険';
    case GROUP_LIFE = '団体生命保険';

    /**
     * バリデーション用の値リストを返す
     */
    public static function values():array
    {
        return array_column(self::cases(), 'value');
    }
}