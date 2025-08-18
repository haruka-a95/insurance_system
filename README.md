# 保険会社向け社内業務システム

本リポジトリは、生命保険会社の社内業務を効率化するためのシステムの一部再現です。
契約管理、請求、営業支援など、保険業務に必要な主要機能を再現しています。

社内向けの基幹システムや営業支援ツールとして利用可能で、拡張性と保守性を重視した設計です。

## 主な機能
- 契約情報の登録・照会・変更管理
- 保全処理（契約変更・解約・異動）
- <未実装>請求・収納管理
- <未実装>代理店情報管理
- <未実装>営業支援（見積作成、進捗管理）
- 各種帳票出力・照会機能

## 技術スタック
- フレームワーク：Laravel(PHP)
- フロントエンド：jQuery/Blade
- データベース：MySQL
- コンテナ管理：Docker / Docker Compose

## セットアップ
1. リポジトリをクローン & ディレクトリ移動
```bash
git clone https://github.com/haruka-a95/insurance_system.git
cd insurance_system
```
2. Dockerイメージのビルド&起動
```bash
docker compose up -d --build
```
#### 構築する環境

- Webコンテナ
  - [php:8.1.14-apache](https://hub.docker.com/_/php)
  - [composer:2.5.1](https://hub.docker.com/_/composer)
- DBコンテナ
  - [mysql:8.0.31](https://hub.docker.com/_/mysql)
- phpMyAdminコンテナ
  - [phpmyadmin:5.2.0](https://hub.docker.com/_/phpmyadmin)

### Laravel
```bash
# ターミナルで実行
## WEBサーバーに入るコマンド（-itの後に入る名称はコンテナ名「{NAME_PREFIX}-web」）
docker exec -it insurance-web bash
```

### composer install
```bash
# ■ WEBサーバーで入力
# 「composer.json」、「composer.lock」に記載されているパッケージをvendorディレクトリにインストール
#   ※ 時間がかかるので注意。
composer install
```

### Node.jsパッケージのインストールとビルド
```bash
# ■ WEBサーバーで入力
npm install
npm run dev
```

### Laravel初期設定
```bash
# ■ WEBサーバーで入力
# 「.env」ファイル
## 「.env.dev」ファイルを「.env」にコピー
cp .env.dev .env
# storage ディレクトリに読み取り・書き込み権限を与える（bootstrap, storage内に書き込み（ログ出力時等）に「Permission denied」のエラーが発生する）
chmod -R 777 bootstrap/cache/
chmod -R 777 storage/
```

### 動作確認
Web画面 URL例: http://localhost:81
※ IP・ポートは .env の IP と PORT_WEB を参照

phpMyAdmin URL例: http://localhost:8081
※ IP・ポートは .env の IP と PORT_PHPMYADMIN を参照

### Laravelのテスト環境設定
- 日本語設定済み
- Laravel Debugbar導入済み
- .env.testing 設定済み

### マイグレーション（テスト用DB）
```php artisan migrate --env=testing```