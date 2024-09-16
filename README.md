# 環境構築
以下ではWSL2（Ubuntu）がインストールされているものとします。
## apacheをインストールする
Apacheのインストール WSLのターミナル（例: Ubuntu）を開いて、次のコマンドを実行します。
```
sudo apt update
sudo apt install apache2
```
次のコマンドでApacheを起動します。
```
sudo service apache2 start
```
ブラウザで次のURLにアクセスして、Apacheが正常に動作しているか確認します。
```
http://localhost
```

ApacheやNginxのドキュメントルートは通常/var/www/htmlにあります。このフォルダにindex.htmlやlmstat.phpなどのファイルを配置して、Webサーバーからアクセスできるようにします。

## PHPをインストールする
以下のコマンドを実行してPHPをインストールします。
```
sudo apt install php libapache2-mod-php
```
 Apacheのデフォルトのドキュメントルートにinfo.phpファイルを作成して、PHPが正しく動作するか確認します。
```
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/info.php
http://localhost/info.php
```

## MySQLをインストールする

# データベース
## MySQLでデータベースを準備する
ライセンスのFeatureの分だけ次のようなテーブルを作成します。

license_usage

|カラム名| id | timestamp | count |
|---| --- | --- | --- |
|型| INT | DATETIME | INT |
|説明| 主キー | 時刻 | ライセンス使用本数 |




```
sudo mysql
```

```
create database lmstat_watcher;
use lmstat_watcher;
CREATE TABLE license_usage (
    ->     id INT AUTO_INCREMENT PRIMARY KEY,
    ->     timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    ->     count INT
    -> );
show tables;
CREATE USER 'lmstat-watcher'@'localhost' IDENTIFIED BY '1111';
grant all privileges on lmstat_watcher.* to 'lmstat-watcher'@'localhost';
```
## MySQLiを有効化する
`php -m | grep mysqli`を実行し、何も表示されなければmysqliをインストール、有効化する必要があります。
```
sudo apt-get install php-mysqli
sudo vim /etc/php/8.1/apache2/php.ini
sudo systemctl restart apache2
```

## cronで定期的にデータベースへデータを格納する
```
crontab -e
```
```
* * * * * php /{PATH TO store_lmstat.php}/store_lmstat.php
```

# 使い方

