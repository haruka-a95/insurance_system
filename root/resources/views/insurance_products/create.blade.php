@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">新規保険作成</h1>

    @if ($errors->any())
    <div class="text-red-500">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- csvから登録 -->
    <form action="{{ route('insurance_product.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
         <label for="csv_file" class="block text-gray-700 font-bold mb-1">CSVファイルから登録</label>
         <input type="file" name="csv_file" accept=".csv" required>
         <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white px-4 py-2 rounded mt-2">アップロード</button>
    </form>
    <!-- csv登録のエラー表示 -->
    @if(session('import_errors') && count(session('import_errors')) > 0)
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
        <p><strong>CSVインポートでエラーが発生しました。</strong></p>
        <table class="table-auto border-collapse border border-gray-400 w-full mt-2 text-sm">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-400 px-2 py-1">行番号</th>
                    <th class="border border-gray-400 px-2 py-1">name</th>
                    <th class="border border-gray-400 px-2 py-1">description</th>
                    <th class="border border-gray-400 px-2 py-1">type</th>
                    <th class="border border-gray-400 px-2 py-1">approval_status</th>
                    <th class="border border-gray-400 px-2 py-1">エラー内容</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('import_errors') as $error)
                    <tr>
                        <td class="border border-gray-400 px-2 py-1">{{ $error['row'] }}</td>
                        <td class="border border-gray-400 px-2 py-1">{{ $error['data']['name'] ?? '' }}</td>
                        <td class="border border-gray-400 px-2 py-1">{{ $error['data']['description'] ?? '' }}</td>
                        <td class="border border-gray-400 px-2 py-1">{{ $error['data']['type'] ?? '' }}</td>
                        <td class="border border-gray-400 px-2 py-1">{{ $error['data']['approval_status'] ?? '' }}</td>
                        <td class="border border-gray-400 px-2 py-1 text-red-600">{{ $error['error'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif


    <!-- 手入力で登録 -->
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
