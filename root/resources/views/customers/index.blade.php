@extends('layouts.app')
@section('title', '顧客一覧')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">顧客一覧</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('customers.create') }}"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
           ＋ 顧客新規登録
        </a>
    </div>

    <div class="mb-4">
        <button id="toggle-search" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
            検索フォーム表示
        </button>

        <div id="search-form" class="mb-6 p-4 border border-gray-300 rounded hidden">
            <form method="GET" action="{{ route('customers.index') }}" class="grid grid-cols-4 gap-4">
                <x-form-input label="名前" name="name" :value="$filters['name'] ?? ''"/>
                <x-form-input label="メール" name="email" :value="$filters['email'] ?? ''"/>
                <x-form-input label="電話番号" name="phone" :value="$filters['phone'] ?? ''"/>
                <x-form-input label="携帯電話" name="cellphone" :value="$filters['cellphone'] ?? ''"/>
                <x-form-date label="更新日～" name="updated_from" :value="$filters['updated_from'] ?? ''" />
                <x-form-date label="更新日期限" name="updated_to" :value="$filters['update_to'] ?? ''" />
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded col-span-1">検索</button>
                <button type="button" id="clear-filters" class="bg-gray-500 text-black px-4 py-2 rounded col-span-1">
                    クリア
                </button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="py-2 px-4 text-left">ID</th>
                    <th class="py-2 px-4 text-left">氏名</th>
                    <th class="py-2 px-4 text-left">メールアドレス</th>
                    <th class="py-2 px-4 text-left">電話番号</th>
                    <th class="py-2 px-4 text-left">住所</th>
                    <th class="py-2 px-4 text-center">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $customer->id }}</td>
                        <td class="py-2 px-4">{{ $customer->name }}</td>
                        <td class="py-2 px-4">{{ $customer->email }}</td>
                        <td class="py-2 px-4">{{ $customer->phone }}</td>
                        <td class="py-2 px-4">{{ $customer->address }}</td>
                        <td class="py-2 px-4 text-center">
                            <a href="{{ route('customers.edit', $customer->id) }}"
                               class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                               編集
                            </a>
                            <form action="{{ route('customers.destroy', $customer->id) }}"
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
                        <td colspan="6" class="text-center py-4">顧客データがありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection