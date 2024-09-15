# 環境構築
## apacheをインストールする


## PHPをインストールする



# データベース
## MySQLでデータベースを準備する


```
sudo mysql
```

```
create database lmstat_watcher;
use lmstat_watcher;
CREATE TABLE license_usage (
    ->     id INT AUTO_INCREMENT PRIMARY KEY,
    ->     timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    ->     output TEXT
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

