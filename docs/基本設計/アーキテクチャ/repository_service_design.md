# アーキテクチャ概要
このアプリケーションでは、リポジトリパターンとサービス層を採用し、ビジネスロジックの責務分離と保守性の向上を目的としています。

## 1. リポジトリパターン（Repository Pattern）
### 目的

Eloquent ORMに依存しすぎない設計を実現

データアクセス処理をコントローラーやサービス層から切り離す

将来的にデータソース（MySQL → APIなど）が変わっても影響を最小限にする

### 構成

```App\Repositories\Contracts\```
各エンティティ（Customer、InsurancePolicy、InsuranceProduct）のインターフェースを定義

```App\Repositories\Eloquent\```
上記インターフェースを実装したEloquentベースのリポジトリクラスを配置

```php
## 例:

interface CustomerRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
```

## 2. サービス層（Service Layer）
### 目的

ビジネスロジックをコントローラーから分離

トランザクション制御や複雑な処理を一元管理

再利用性・テスト容易性を向上

### 構成

```App\Services\```
各エンティティごとにServiceクラスを作成
Repositoryを呼び出しつつ、必要に応じてDB::transactionやログ記録を実施

```php
例:

class CustomerService
{
    protected $customerRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function updateCustomer(int $id, array $data)
    {
        DB::transaction(function() use ($id, $data) {
            $this->customerRepo->update($id, $data);
        });
    }
}
```

## 3. メリット
- 疎結合な構成
- コントローラーがデータアクセス実装（Eloquent）に依存しない
- テストが容易
- リポジトリをMock化したユニットテストが可能
- 責務分離
- コントローラーは入出力処理、リポジトリはデータ操作、サービスはビジネスロジックと役割が明確化

## 4. ディレクトリ構成
```bash
app/
 ├── Http/
 │   ├── Controllers/
 │   ├── Requests/
 │
 ├── Models/
 ├── Repositories/
 │   ├── Contracts/
 │   └── Eloquent/
 │
 ├── Services/
```