# アーキテクチャ概要
このアプリケーションでは、リポジトリパターンとサービス層を採用し、ビジネスロジックの責務分離と保守性の向上を目的としています。

## 1. リポジトリパターン（Repository Pattern）
### 目的

- Eloquent ORMに依存しすぎない設計を実現
- データアクセス処理をコントローラーやサービス層から切り離す
- 将来的にデータソース（MySQL → APIなど）が変わっても影響を最小限にする

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

- ビジネスロジックをコントローラーから分離
- トランザクション制御や複雑な処理を一元管理
- 再利用性・テスト容易性を向上

### 構成

```App\Services\```
- 各エンティティごとにServiceクラスを作成
- Repositoryを呼び出しつつ、必要に応じてDB::transactionやログ記録を実施
- 検索専用のサービスクラス（例: InsurancePolicySearchService）もこの階層に配置し、複雑な検索条件を集約

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

## 3. コントローラー層
#### 役割
- リクエストの受け取り・バリデーション（FormRequest使用）
- サービス層の呼び出し
- ビューやAPIレスポンスの返却
- ビジネスロジックは持たない

## 4. 依存関係とDIルール
- Controller → Service → Repository → Model の一方向依存
- インターフェースと実装クラスのバインドは ```AppServiceProvider``` または専用の ```ServiceProvider``` に記述
- ```new``` で直接依存を作らず、Laravelのサービスコンテナを経由して依存解決

## 5. メリット
- 疎結合な構成
- コントローラーがデータアクセス実装（Eloquent）に依存しない
- テストが容易
- リポジトリをMock化したユニットテストが可能
- 責務分離により可読性・保守性向上
- コントローラーは入出力処理、リポジトリはデータ操作、サービスはビジネスロジックと役割が明確化

## 6. ユースケースクラス（Use Case）
### 目的
- アプリケーションの具体的な「業務フロー」や「操作単位」を表現
- 複数のサービスクラスを組み合わせて業務の流れをコントロール
- 画面や機能ごとのユースケース単位で処理をまとめ、保守性とテスト容易性を向上

| 層         | 役割例                                  |
| --------- | ------------------------------------ |
| ユースケースクラス | 「保険契約CSVエクスポート」などの具体的な業務フローを担当       |
| サービスクラス   | 「保険契約の検索」「顧客情報更新」など、単一責任のビジネスロジックを担当 |

### 例
```php
class ExportInsurancePolicyCsvUseCase
{
    protected InsurancePolicySearchService $searchService;

    public function __construct(InsurancePolicySearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function handle(array $filters)
    {
        // 複雑な検索ロジックはsearchServiceに任せてデータ取得
        $policies = $this->searchService->searchAll($filters);

        // CSV出力の業務フローをここで実装
        // ...
    }
}
```


## 7. ディレクトリ構成
```bash
app/
 ├── Http/
 │   ├── Controllers/
 │   ├── Requests/
 │
 ├── Models/
 │
 ├── Repositories/
 │   ├── Contracts/   #インターフェース
 │   └── Eloquent/    #実装
 ｜
 ├── UseCases/
 │    ├── FetchInsurancePoliciesUseCase.php
 │    └── ExportInsurancePolicyCsvUseCase.php
 ｜
 ├── Services/   #← 既存サービスは段階的にUseCasesへ移行を検討
 │   ├── InsurancePolicyService.php
 │   ├── InsurancePolicySearchService.php
 │   ├── CustomerService.php
 │   └── ...
 │
 └── Providers/
     ├── AppServiceProvider.php
     └── RepositoryServiceProvider.php
```