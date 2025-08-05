@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">顧客編集</h1>

    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form-input label="名前" name="name" :value="old('name', $customer->name)" />
        <x-form-date label="誕生日" name="birthday" required="true" :value="$customer->birthday ?? ''" />
        <x-form-input label="自宅電話番号" name="phone" :value="old('phone', $customer->phone)" />
        <x-form-input label="携帯電話番号" name="cellphone" :value="old('cellphone', $customer->cellphone)" />
        <x-form-input label="メールアドレス" name="email" type="email" :value="old('email', $customer->email)" />
        <x-form-input label="住所" name="address" type="text" :value="old('address', $customer->address)" />

        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded mt-4">
            更新
        </button>
        <a href="{{ route('customers.index') }}" class="ml-4 text-gray-600 hover:underline">キャンセル</a>
    </form>
</div>
@endsection
