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
            <!-- ステータス -->
               @php
                $statusOptions = [];
                foreach (\App\Enums\PolicyStatus::cases() as $case) {
                    $statusOptions[$case->value] = $case->label();
                }
                @endphp
                <x-form-multi-select label="ステータス" name="status" helpText="以下から選択してください。（複数選択可）" :options="$statusOptions" :selected="old('status', $filters['status'] ?? [])" multiple />
            <!-- 開始日 -->
              <x-form-date label="開始日（以降）" name="start_date_from"/>
              <x-form-date label="開始日（以前）" name="start_date_to"/>
            <!-- 満了日 -->
              <x-form-date label="満了日（以降）" name="end_date_from"/>
              <x-form-date label="満了日（以前）" name="end_date_to"/>
            <!-- 保険料 -->
             <x-form-input label="保険料下限" placeholder="例: 10000" type="number" name="amount_min" :value="old('amount_min')" />
             <x-form-input label="保険料上限" placeholder="例: 10000" type="number" name="amount_max" :value="old('amount_min')" />
            <!-- 並び替え -->
            @php
            $sortOptions = [
                '' => '指定なし',
                'start_date_asc' => '開始日 昇順',
                'start_date_desc' => '開始日 降順',
                'end_date_asc' => '満了日 昇順',
                'end_date_desc' => '満了日 降順',
                'premium_amount_asc' => '保険料 昇順',
                'premium_amount_desc' => '保険料 降順',
                'status_asc' => 'ステータス昇順',
                'status_desc' => 'ステータス降順',];
            @endphp
                <x-form-select label="並び替え" name="sort_by" :options="$sortOptions"
                 :selected="old('sort_by', request('sort_by'))"/>
                <!-- ボタン -->
                <div class="col-span-4 flex gap-2 justify-end">
                    <button type="submit" formaction="{{ route('insurance_policies.export_filtered') }}" class="bg-lime-500 hover:bg-lime-700 text-white px-4 py-2 rounded">この検索条件でCSV出力</button>
                    <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white px-4 py-2 rounded col-span-1">検索</button>
                    <button type="button" id="clear-filters" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded col-span-1">
                        クリア
                    </button>
                </div>
         </form>
     </div>

    <!-- バリデーションエラー用フラグ -->
     @if ($errors->any())
     <script>window.hasSearchErrors = true;</script>
     @endif

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
