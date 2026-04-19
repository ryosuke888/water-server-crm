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

## 2026-04-03

### やったこと
- Policy / Gate を使った権限制御の実装
- Bladeでの権限による表示制御の実装
- ServiceProviderでのGate定義を理解

---

### 学んだこと

#### ■ Policyによる認可
- コントローラーで認可処理を実行

```php
$this->authorize('update', $order);
```

## 2026-04-08

### やったこと
- Featureテストの基本的な流れを理解
- OrderStatusの状態遷移のルールを追加
- ルーティングの整理

---

### 学んだこと

#### ■　状態遷移のルール
- Enumを用いてルール実装

## 2026-04-9

### やったこと
- Featureテストの理解を深めた
- Factoryを使ったテストデータ生成を実装

---

### 学んだこと

#### ■ Featureテスト
- 実際のリクエストを通して処理が正しく動くかを確認するテスト

#### ■ Factory
- stateを利用し、様々な状態のテストケースを作成

## 2026-04-10

### やったこと
- FeatureテストでCSVアップロード処理を実装
- UploadedFile::fake() を使ったファイルテストを実装

---

### 学んだこと

#### ■ UploadedFile::fake()
- テスト用のファイルを擬似的に作成できる

```php
UploadedFile::fake()->createWithContent('test.csv', $csvContent);
```

## 2026-04-11

### やったこと
- 親Requestを使ったバリデーションの実装と継承
- バリデーションエラーの例外処理を理解
- ValidationExceptionを用いたエラーハンドリングを実装

---

### 学んだこと

#### ■ 抽象クラスの役割
- `basicRules()` にバリデーションルールを定義し子クラスで継承

## 2026-04-12

### やったこと
- Featureテストの修正
- OrderServiceのリファクタリング

### 気づき
- バリデーション・処理・登録を分離する必要性を理解を深めた


## 2026-04-13

### やったこと
- Bladeコンポーネントのpropsの使い方を理解
- コンポーネントへのデータ受け渡しを実装

---

### 学んだこと

#### ■ Bladeコンポーネントのprops
- コンポーネントに値を渡すための仕組み

## 2026-04-15

### やったこと
- ボタンコンポーネントの作成
- bladeのリファクタリング

### 学んだこと
- slotとpropsの違い

## 2026-04-16

### やったこと
- FormRequestでGateを使った認可処理を実装
- authorizeメソッドの役割を理解

---

### 学んだこと

#### ■ FormRequestのauthorize
- リクエスト時に認可チェックを行うことができる


## 2026-04-18

### やったこと
- JavaScriptの非同期処理（async / await）を理解
- API通信によるデータ取得処理を実装

---

### 学んだこと

#### ■ 非同期処理（async / await）
- データ取得など時間がかかる処理を非同期で実行できる

## 2026-04-19

### やったこと
- JavaScriptの非同期処理（async / await）を理解
- API通信によるデータ取得処理を実装の続き

---

### 詰まったところ
- fetchで取得したデータの扱い方が分かりにくかった

---

### 学び・気づき
- async / await を使うことで非同期処理を直感的に書け

