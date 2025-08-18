<?php
namespace App\Services;

use App\Models\InsurancePolicy;

class InsurancePolicySearchService
{
    public function search(array $filters, int $perPage = 20)
    {
        $query = InsurancePolicy::query()->with(['customer:id,name', 'products:id,name']);

        //成約番号
        if (!empty($filters['policy_number'])) {
            $query->where('policy_number', 'like', '%'. $filters['policy_number'] . '%');
        }

        //顧客番号
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', 'like', '%' . $filters['customer_id'] . '%');
        }

        //関連製品名
        if (!empty($filters['product_name'])) {
            $query->whereHas('products', function($q) use ($filters){
                $q->where('name', 'like', '%' . $filters['product_name']  . '%');
            });
        }

        //開始日範囲
        if (!empty($filters['start_date_from']) && !empty($filters['start_date_to'])) {
            $query->whereBetween('start_date', [
                $filters['start_date_from'],
                $filters['start_date_to']
            ]);
        } elseif (!empty($filters['start_date_from'])) {
            $query->where('start_date', '>=', $filters['start_date_from']);
        } elseif (!empty($filters['start_date_to'])) {
            $query->where('start_date', '<=', $filters['start_date_to']);
        }

        //満了日範囲
        if (!empty($filters['end_date_from']) && !empty($filters['end_date_to'])) {
            $query->whereBetween('end_date', [
                $filters['end_date_from'],
                $filters['end_date_to']
            ]);
        } elseif (!empty($filters['end_date_from'])) {
            $query->where('end_date', '>=', $filters['end_date_from']);
        } elseif (!empty($filters['end_date_to'])) {
            $query->where('end_date', '<=', $filters['end_date_to']);
        }

        //保険料 範囲
        if (!empty($filters['amount_min']) && !empty($filters['amount_max'])) {
            $query->whereBetween('premium_amount',[
                $filters['amount_min'],
                $filters['amount_max']
            ]);
        } elseif (!empty($filters['amount_min'])) {
            $query->where('premium_amount', '>=', $filters['amount_min']);
        } elseif (!empty($filters['amount_max'])) {
            $query->where('premium_amount', '<=', $filters['amount_max']);
        }

        //ステータス
        if (!empty($filters['status']) && is_array($filters['status'])) {
            $status = array_filter($filters['status']);
            if (!empty($status)) {
                $query->whereIn('status', $status);
            }
        }

        return $query->paginate(20);
    }

    public function searchAll(array $filters)
    {
        $query = InsurancePolicy::query()->with(['customer:id,name']);

        //成約番号
        if (!empty($filters['policy_number'])) {
            $query->where('policy_number', 'like', '%'. $filters['policy_number'] . '%');
        }

        //顧客番号
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', 'like', '%' . $filters['customer_id'] . '%');
        }

        //関連製品名
        if (!empty($filters['product_name'])) {
            $query->whereHas('products', function($q) use ($filters){
                $q->where('name', 'like', '%' . $filters['product_name']  . '%');
            });
        }

        //開始日範囲
        if (!empty($filters['start_date_from']) && !empty($filters['start_date_to'])) {
            $query->whereBetween('start_date', [
                $filters['start_date_from'],
                $filters['start_date_to']
            ]);
        } elseif (!empty($filters['start_date_from'])) {
            $query->where('start_date', '>=', $filters['start_date_from']);
        } elseif (!empty($filters['start_date_to'])) {
            $query->where('start_date', '<=', $filters['start_date_to']);
        }

        //満了日範囲
        if (!empty($filters['end_date_from']) && !empty($filters['end_date_to'])) {
            $query->whereBetween('end_date', [
                $filters['end_date_from'],
                $filters['end_date_to']
            ]);
        } elseif (!empty($filters['end_date_from'])) {
            $query->where('end_date', '>=', $filters['end_date_from']);
        } elseif (!empty($filters['end_date_to'])) {
            $query->where('end_date', '<=', $filters['end_date_to']);
        }

        //保険料 範囲
        if (!empty($filters['amount_min']) && !empty($filters['amount_max'])) {
            $query->whereBetween('premium_amount',[
                $filters['amount_min'],
                $filters['amount_max']
            ]);
        } elseif (!empty($filters['amount_min'])) {
            $query->where('premium_amount', '>=', $filters['amount_min']);
        } elseif (!empty($filters['amount_max'])) {
            $query->where('premium_amount', '<=', $filters['amount_max']);
        }

        //ステータス
        if (!empty($filters['status']) && is_array($filters['status'])) {
            $query->whereIn('status', $filters['status']);
        }

        return $query->get(); //全件取得
    }
}