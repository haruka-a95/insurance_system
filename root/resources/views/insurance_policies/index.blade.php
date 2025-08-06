@extends('layouts.app')
@section('title', '保険ポリシー一覧')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">保険ポリシー一覧</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('insurance_policies.create') }}"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
           ＋ 新規登録
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="py-2 px-4 text-left">ID</th>
                    <th class="py-2 px-4 text-left">成約番号</th>
                    <th class="py-2 px-4 text-left">顧客番号</th>
                    <th class="py-2 px-4 text-left">関連製品番号</th>
                    <th class="py-2 px-4 text-left">開始日</th>
                    <th class="py-2 px-4 text-left">満了日</th>
                    <th class="py-2 px-4 text-left">金額</th>
                    <th class="py-2 px-4 text-left">ステータス</th>
                    <th class="py-2 px-4 text-center">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($insurancePolicies as $policy)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $policy->id }}</td>
                        <td class="py-2 px-4">{{ $policy->policy_number }}</td>
                        <td class="py-2 px-4">{{ $policy->customer_id }}</td>
                        <td class="py-2 px-4">
                            @if($policy->products->isNotEmpty())
                            @foreach($policy->products as $product)
                                <span class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded mr-1">{{ $product->name }}</span>
                            @endforeach
                            @else
                            <span class="text-gray-400">製品なし</span>
                            @endif
                        </td>
                        <td class="py-2 px-4">{{ $policy->start_date }}</td>
                        <td class="py-2 px-4">{{ $policy->end_date }}</td>
                        <td class="py-2 px-4">{{ $policy->premium_amount }}</td>
                        <td class="py-2 px-4">{{ $policy->status_label }}</td>
                        <td class="py-2 px-4 text-center">
                            <a href="{{ route('insurance_policies.edit', $policy->id) }}"
                               class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                               編集
                            </a>
                            <form action="{{ route('insurance_policies.destroy', $policy->id) }}"
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded">
                                    削除
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">データがありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
