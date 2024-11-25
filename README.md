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



|カラム名| id | timestamp | license_server | feature | usage_count |
|---| --- | --- | --- | --- | --- |
|型| INT | DATETIME | TEXT | TEXT | INT |
|説明| 主キー | 時刻 | ライセンスサーバホスト名 | フィーチャー名 | ライセンス使用本数 |




```
sudo mysql
```

```
mysql> create database flexnet_watcher;
mysql> use flexnet_watcher;
mysql> create table license_usage (
    -> id int auto_increment primary key,
    -> timestamp datetime default current_timestamp,
    -> license_server text,
    -> feature text,
    -> usage_count int);
mysql> show tables;
mysql> create user 'flexnet_watcher'@'localhost' identified by '1111';
mysql> grant all privileges on flexnet_watcher.* to 'flexnet_watcher'@'localhost';
mysql> quit;
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

