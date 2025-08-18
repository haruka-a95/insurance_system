@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">保険詳細</h1>

    <div class="mb-2"><strong>ID:</strong> {{ $insuranceProduct->id }}</div>
    <div class="mb-2"><strong>カテゴリ:</strong> {{ $insuranceProduct->type }}</div>
    <div class="mb-2"><strong>ステータス:</strong> {{ $insuranceProduct->approval_status }}</div>
    <div class="mb-2"><strong>名称:</strong> {{ $insuranceProduct->name }}</div>
    <div class="mb-2"><strong>説明:</strong> {{ $insuranceProduct->description }}</div>
    <div class="mb-2"><strong>作成日:</strong> {{ $insuranceProduct->created_at->format('Y-m-d H:i') }}</div>
    <div class="mb-4"><strong>更新日:</strong> {{ $insuranceProduct->updated_at->format('Y-m-d H:i') }}</div>

    <a href="{{ route('insurance_products.edit', $insuranceProduct) }}" class="bg-yellow-500 text-white px-4 py-2 rounded mr-2">
        編集
    </a>
    <a href="{{ route('insurance_products.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">
        一覧に戻る
    </a>
</div>
@endsection
