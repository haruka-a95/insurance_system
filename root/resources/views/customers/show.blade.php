@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">顧客詳細</h1>

    <div class="mb-2"><strong>ID:</strong> {{ $customer->id }}</div>
    <div class="mb-2"><strong>名前:</strong> {{ $customer->name }}</div>
    <div class="mb-2"><strong>メールアドレス:</strong> {{ $customer->email }}</div>
    <div class="mb-2"><strong>電話番号:</strong> {{ $customer->phone }}</div>
    <div class="mb-2"><strong>作成日:</strong> {{ $customer->created_at->format('Y-m-d H:i') }}</div>
    <div class="mb-4"><strong>更新日:</strong> {{ $customer->updated_at->format('Y-m-d H:i') }}</div>

    <a href="{{ route('customers.edit', $customer) }}" class="bg-yellow-500 text-white px-4 py-2 rounded mr-2">
        編集
    </a>
    <a href="{{ route('customers.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">
        一覧に戻る
    </a>
</div>
@endsection
