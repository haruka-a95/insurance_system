@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">新規ポリシー作成</h1>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('insurance_policies.store') }}" method="POST">
        @csrf

        <!-- 成約番号 -->
        <x-form-input label="成約番号" name="policy_number" :value="old('policy_number')" />
        <!-- 顧客選択 -->
        <x-form-select
            label="顧客"
            name="customer_id"
            :options="$customers->pluck('name','id')->toArray()"
            :selected="old('customer_id')"
        />
        <!-- 保険製品の複数選択 -->
        <label class="block text-gray-700 font-bold mb-2">保険製品</label>
        <select name="product_ids[]" multiple
                class="w-full border rounded px-3 py-2 mb-4">
            @foreach($products as $product)
                <option value="{{ $product->id }}"
                    {{ (collect(old('products'))->contains($product->id)) ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
        @error('products')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <!-- 開始日 -->
        <x-form-input type="date" label="開始日" name="start_date" :value="old('start_date')" />

        <!-- 満了日 -->
        <x-form-input type="date" label="満了日" name="end_date" :value="old('end_date')" />

        <!-- 保険料 -->
        <x-form-input type="number" step="0.01" label="金額" name="premium_amount" :value="old('premium_amount')" />

        <!-- ステータス -->
        @php
        $statusOptions = [];
        foreach (\App\Enums\PolicyStatus::cases() as $case) {
            $statusOptions[$case->value] = $case->label();
        }
        @endphp
        <x-form-select label="ステータス" name="status" :options="$statusOptions" :selected="old('status')" />

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                作成
            </button>
            <a href="{{ route('insurance_policies.index') }}" class="ml-4 text-gray-600 hover:underline">キャンセル</a>
        </div>
    </form>
</div>
@endsection
