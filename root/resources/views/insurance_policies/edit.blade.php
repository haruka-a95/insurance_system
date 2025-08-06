@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">保険編集</h1>

    <form action="{{ route('insurance_products.update', $insuranceProduct) }}" method="POST">
        @csrf
        @method('PUT')

        @php
        $options = [];
        foreach (\App\Enums\InsuranceType::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        @endphp
        <x-form-select label="保険カテゴリ" name="type" :options="$options" :selected="old('type', $insurancePolicy->type ?? '')"/>
        <x-form-input label="名称" name="name" :value="old('name', $insuranceProduct->name)" />
        <x-form-input label="説明" name="description" type="text" :value="old('description', $insuranceProduct->description)" />

        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded mt-4">
            更新
        </button>
        <a href="{{ route('insurance_products.index') }}" class="ml-4 text-gray-600 hover:underline">キャンセル</a>
    </form>
</div>
@endsection
