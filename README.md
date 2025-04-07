# COACHTECH勤怠管理アプリ
登録した勤怠情報を管理者を介して管理することができるアプリ

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/

## テストユーザー
| ユーザー名 | メールアドレス | パスワード | 
| ---------- | -------------- | ---------- | 
| 一郎       | ichirou@example.com | password   | 
| 二郎       | jirou@example.com   | password   | 
| 三郎       | saburou@example.com | password   | 
| 四郎       | shirou@example.com  | password   | 
| 五郎       | gorou@example.com   | password   | 
| ---------- | -------------- | ---------- | 
| 管理者     | admin@exmaple.com   | password   | 

## 機能一覧
- 会員登録
- ログイン
- ログアウト
- 勤怠登録
- 勤怠一覧画面の表示
- 勤怠詳細画面の表示
- 勤怠情報の修正申請(ユーザー)
- 申請一覧画面の表示
- 管理者ログイン
- 勤怠情報の修正(管理者)
- 修正申請の承認(管理者)
- スタッフ一覧画面の表示(管理者)

## 使用技術(実行環境)
- PHP8.3.0
- Laravel8.83.27
- MySQL8.0.26

## テーブル設計
![alt](table1.png)
![alt](table2.png)

## ER図
![alt](er.png)

# 環境構築
**Dockerビルド**
1. `git clone git@github.com:matashi163/COACHTECH-Attendance.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

> *MacのM1・M2チップのPCの場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。
エラーが発生する場合は、docker-compose.ymlファイルの「mysql」内に「platform」の項目を追加で記載してください*
``` bash
mysql:
    platform: linux/x86_64(この文追加)
    image: mysql:8.0.26
    environment:
```

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```

8. シンボリックリンク作成
``` bash
php artisan storage:link
```