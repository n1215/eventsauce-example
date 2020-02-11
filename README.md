# EventSauceの利用サンプル

イベントソーシングのライブラリ[EventSauce](https://eventsauce.io/)を利用するサンプルコード。

[PHPerKaigi 2020の発表スライド](https://speakerdeck.com/n1215/phptoeventsaucedeshi-meruibentososinguapurikesiyon)用。

## 内容
- ソーシャルゲームにおいてユーザ所持キャラクターを獲得、強化する処理の簡略版
- PHP >= 7.4
- CQRSの適用はなし
- インメモリのMessageRepositoryを利用
- 同期処理のMessageDispatcherを利用
- BDDスタイルのテストコード

## ディレクトリ構成
- bin/index.php : コンソールからの実行用のファイル
- src
  - Commands/ : コマンド
  - Consumers/ : コンシューマ
  - Events/ : イベント
  - Exceptions/ : 例外
  - ※ 集約およびID群は `src` 直下
- tests/ : テストコード

## 使い方

### 1. リポジトリをクローン
```
git clone https://github.com/n1215/eventsauce-example.git
```

### 2. 依存をインストール
```
cd eventsauce-example
composer install
```

### 3. 実行
```
php bin/index.php
```

### 4. テスト実行

```
vendor/bin/phpunit --testdox
```
