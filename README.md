# Water Server CRM

## 概要
コールセンター業務で感じた課題を解決するために開発したCRMシステムです。

## 課題
- 同じ情報を何度も入力する必要がある
- Excelでの管理が多く非効率
- CRM画面の動作が遅い
- 配送業者への連絡が手動
- ログを手動で入力する必要がある

## 解決
- 入力項目の削減・自動補完
- 顧客・受注情報の一元管理
- 検索・一覧表示の高速化
- 操作ログの自動記録
- CSVによる一括取り込み対応
- 配送業者とのAPI連携（予定）

## 機能
- ログイン機能
- 顧客管理
- 受注管理
- 検索機能
- ログ管理
- 権限管理
- CSV取込

## 技術スタック
- Laravel
- MySQL
- Docker (Laravel Sail)
- Vue（予定）

## 環境構築

```bash
git clone https://github.com/ryosuke888/water-server-crm.git
cd water-server-crm
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate

## 画面
（スクショ貼る）
