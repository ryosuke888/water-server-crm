## 2026-03-27

### やったこと
- Eloquentのリレーション実装
- $customer->load() の理解

### 詰まったところ
- コレクションと配列の違いが曖昧だった

### 学んだこと
- loadを使うとN+1を防げる
- リレーションでデータ取得がシンプルになる

## 2026-03-28

### やったこと
- redirect と view の違いを理解
- EloquentでのEager Loadingを実装
- JSでLaravelのデータを扱う処理を実装（@json）
- 価格データの整形処理（mapWithKeys）

---

### 学んだこと

#### ■ redirect と view の違い
- view：そのまま画面を返す（例：リスト画面）
- redirect：別のURLに遷移させる（例：リストに戻る）

---

#### ■ Eager Loading
```php
$planProductPrices->with(['plan', 'product'])->get();
```

## 2026-03-29

### やったこと
- マイグレーションの理解（カラム定義・制約）
- 外部キー制約（foreignId）の実装
- enumによるステータス管理の設計

---

### 学んだこと

#### ■ protected $casts
- DBの値を自動的に型変換できる仕組み
- booleanやdatetimeなどを適切な型で扱える

---

#### ■ foreignId
- `foreignId()->constrained()` を使うことで、関連テーブルと紐付けできる

```php
$table->foreignId('customer_id')->constrained();
```

## 2026-03-30

### やったこと
- `with` と `load` の違いを理解
- Enumクラスを作成し、ステータス管理を実装

---

### 学んだこと

#### ■ with と load の違い
- `with`：クエリ実行時にリレーションを一緒に取得（Eager Loading）
- `load`：取得済みのモデルに対して後からリレーションを読み込む

#### ■ Enum + casts の連携
- モデルの $casts に設定することで、自動的にEnumへ変換される

```php
// with（取得時）
Customer::with('orders')->get();

// load（取得後）
$customer->load('orders');
```

## 2026-03-31

### やったこと
- pagination（ページネーション）の仕組みを理解
- factory / seeder を使ったテストデータ作成を実装

---

### 学んだこと

#### ■ paginationの仕組み
- DBからデータを分割して取得する仕組み
- `limit` と `offset` を使ってデータを取得している
- `count` クエリで総件数を取得し、ページ数を算出

```php
Customer::orderBy('id')->paginate(10);
```

## 2026-04-01

### やったこと
- `when` を使った条件付きクエリの実装
- CSVファイルの取り込み処理を実装（SplFileObject）

---

### 学んだこと

#### ■ whenを使った条件分岐クエリ
- 条件がある場合のみクエリを追加できる

```php
$query->when($keyword, function ($query) use ($keyword) {
    $query->where('name', 'like', "%{$keyword}%");
});
```

## 2026-04-02

### やったこと
- CSV処理における配列操作（array_map / array_filter）の理解
- ヘッダー行とデータ行を組み合わせ配列を作成(array_combine)の理解
- Validatorを使ったCSVデータのバリデーション実装
- ループ処理（foreach）の使い分けを整理

---

### 学んだこと

#### ■ array_map / array_filter
- `array_map`：配列の各要素を変換する
- `array_filter`：条件に合う要素のみ抽出する

```php
array_map(fn($value) => trim($value), $row);
```
