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

    <div class="mb-4 flex gap-4 justify-end">
        <a href="{{ route('insurance_policies.create') }}"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 block rounded">
           ＋ 新規登録
        </a>
        <button id="toggle-search" class="bg-sky-500 hover:bg-sky-700 font-bold text-white py-2 px-4 rounded">
            検索フォーム表示
        </button>
    </div>

    <!-- 検索フォーム -->
     <div id="search-form" class="mb-6 p-4 border border-gray-300 rounded hidden">
         <form method="GET" action="{{ route('insurance_policies.index') }}" class="mb-2 grid grid-cols-4 gap-4">
            <!-- 成約番号 -->
             <x-form-input label="成約番号" name="policy_number" :value="old('policy_number')" />
             <!-- 顧客番号 -->
             <x-form-input label="顧客番号" name="customer_id" :value="old('customer_id')" />
             <!-- 関連商品名 -->
             <x-form-input label="関連商品" name="product_name" :value="old('product_name')" />
             <!-- 開始日 -->
              <x-form-date label="開始日" name="start_date_from"/>
              <x-form-date label="開始日" name="start_date_to"/>
              <!-- 満了日 -->
               <x-form-date label="満了日" name="end_date_from"/>
               <x-form-date label="満了日" name="end_date_to"/>
               <!-- ステータス -->
               @php
                $statusOptions = [];
                foreach (\App\Enums\PolicyStatus::cases() as $case) {
                    $statusOptions[$case->value] = $case->label();
                }
                @endphp
                <x-form-select label="ステータス" name="status[]" :options="$statusOptions" :selected="old('status', $filters['status'] ?? [])" multiple />
              <!-- 保険料 -->
               <div class="flex flex-col gap-2">
                    <label for="amount_min" class="block text-gray-700 font-bold mb-2">保険料下限</label>
                    <input type="number" name="amount_min" placeholder="例: 10000" value="{{ request('amount_min') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
               </div>
               <div class="flex flex-col gap-2">
                    <label for="amount_max" class="block text-gray-700 font-bold mb-2">保険料上限</label>
                    <input type="number" name="amount_max" placeholder="例: 500000" value="{{ request('amount_max') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
               </div>
               <!-- 並び替え -->
                <div class="flex flex-col gap-1">
                    <label for="sort_by" class="block text-gray-700 font-bold mb-2">並び替え</label>
                    <select id="sort_by" name="sort_by" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mt-1">
                        <option value="">指定なし</option>
                        <option value="start_date_asc" {{ request('sort_by') == 'start_date_asc' ? 'selected' : '' }}>開始日 昇順</option>
                        <option value="start_date_desc" {{ request('sort_by') == 'start_date_desc' ? 'selected' : '' }}>開始日 降順</option>
                        <option value="end_date_asc" {{ request('sort_by') == 'end_date_asc' ? 'selected' : '' }}>満了日 昇順</option>
                        <option value="end_date_desc" {{ request('sort_by') == 'end_date_desc' ? 'selected' : '' }}>満了日 降順</option>
                        <option value="premium_amount_asc" {{ request('sort_by') == 'premium_amount_asc' ? 'selected' : '' }}>保険料 昇順</option>
                        <option value="premium_amount_desc" {{ request('sort_by') == 'premium_amount_desc' ? 'selected' : '' }}>保険料 降順</option>
                        <option value="status_asc" {{ request('sort_by') == 'status_asc' ? 'selected' : '' }}>ステータス昇順</option>
                        <option value="status_desc" {{ request('sort_by') == 'status_desc' ? 'selected' : '' }}>ステータス降順</option>
                    </select>
                </div>
                <div class="col-span-4 flex gap-2 justify-end">
                    <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white px-4 py-2 rounded col-span-1">検索</button>
                    <button type="button" id="clear-filters" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded col-span-1">
                        クリア
                    </button>
                    <button type="submit" formaction="{{ route('insurance_policies.export_filtered') }}" class="bg-lime-500 hover:bg-lime-700 text-white px-4 py-2 rounded">検索条件でCSV出力</button>
                </div>
         </form>
     </div>

     <!-- CSV出力 -->
      <a href="{{ route('insurance_policies.export_csv', request()->query()) }}" class="bold bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded mt-6 mb-2">CSV出力(全件)</a>
    <!-- 一覧 -->
    <div class="overflow-x-auto mt-6">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="py-2 px-4 text-left">ID</th>
                    <th class="py-2 px-4 text-left">成約番号</th>
                    <th class="py-2 px-4 text-left">顧客番号</th>
                    <th class="py-2 px-4 text-left">関連製品</th>
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
                        <td class="py-2 px-4 text-center flex gap-2">
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
    <div class="m-2">
        {{ $insurancePolicies->withQueryString()->links() }}
    </div>
</div>
@endsection
