@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">新規保険作成</h1>

    <form action="{{ route('insurance_products.store') }}" method="POST">
        @csrf

        <x-form-input label="名前" name="name" :value="old('name')" />
        <x-form-textarea label="説明" rows="4" name="description" :value="old('description')" />
        @php
        $options = [];
        foreach (\App\Enums\InsuranceType::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        @endphp
        <x-form-select label="保険カテゴリ" name="type" :options="$options" :selected="old('type', $insurancePolicy->type ?? '')"/>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">
            作成
        </button>
        <a href="{{ route('insurance_products.index') }}" class="ml-4 text-gray-600 hover:underline">キャンセル</a>
    </form>
</div>
@endsection
