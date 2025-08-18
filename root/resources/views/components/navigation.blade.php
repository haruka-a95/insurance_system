<nav class="bg-blue-600 text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <ul class="flex space-x-6">
            <li><a href="/">HOME</a></li>
            <li><a href="{{ route('customers.index') }}" class="hover:underline">顧客一覧</a></li>
            <li><a href="{{ route('insurance_products.index') }}">保険製品一覧</a></li>
            <li><a href="{{ route('insurance_policies.index') }}">契約状況一覧</a></li>
        </ul>
    </div>
</nav>
