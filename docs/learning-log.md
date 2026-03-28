## 2026-03-27

### やったこと
- Eloquentのリレーション実装
- $customer->load() の理解

### 詰まったところ
- コレクションと配列の違いが曖昧だった

### 学んだこと
- loadを使うとN+1を防げる
- リレーションでデータ取得がシンプルになる

## 2026-03-29

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
