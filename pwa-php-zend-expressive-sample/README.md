# Elastic Beanstalk を使って５分〜１０分で立ち上げに挑戦！

すぐに、アプリ開発を進めて頂けるように、[Zend Expressive](https://zendframework.github.io/zend-expressive/)
ベースにAmazon Paymentsの開発が始められるアプリケーションを作りました。

このアプリケーションを、Elastic Beanstalkを使って素早く立ち上げてみよう！ 最短５分で！ ^^;

### 事前にこれらをインストールしておいてください

* [AWSCLI](https://aws.amazon.com/jp/cli/)
* [EB CLI](http://docs.aws.amazon.com/ja_jp/elasticbeanstalk/latest/dg/eb-cli3.html)

### アプリケーションの準備

1. git clone をしよう

```shell

$ git clone https://github.com/johna1203/pwa-aws-summit.git
Cloning into 'pwa-aws-summit'...
remote: Counting objects: 72, done.
remote: Compressing objects: 100% (51/51), done.
remote: Total 72 (delta 3), reused 69 (delta 3), pack-reused 0
Unpacking objects: 100% (72/72), done.
Checking connectivity... done.

```

2. EB CLIの設定

私は、このように設定しました。
phpのバージョンは ** PHP 5.5 ** 以上に設定してください！！！

```shell

$ cd pwa-aws-summit/pwa-php-zend-expressive-sample
$ eb init

Select a default region
1) us-east-1 : US East (N. Virginia)
2) us-west-1 : US West (N. California)
3) us-west-2 : US West (Oregon)
4) eu-west-1 : EU (Ireland)
5) eu-central-1 : EU (Frankfurt)
6) ap-southeast-1 : Asia Pacific (Singapore)
7) ap-southeast-2 : Asia Pacific (Sydney)
8) ap-northeast-1 : Asia Pacific (Tokyo)
9) ap-northeast-2 : Asia Pacific (Seoul)
10) sa-east-1 : South America (Sao Paulo)
11) cn-north-1 : China (Beijing)
(default is 3): 8

Enter Application Name
(default is "pwa-php-zend-expressive-sample"):
Application pwa-php-zend-expressive-sample has been created.

Select a platform.
1) Node.js
2) PHP
3) Python
4) Ruby
5) Tomcat
6) IIS
7) Docker
8) Multi-container Docker
9) GlassFish
10) Go
11) Java
(default is 1): 2

Select a platform version.
1) PHP 5.4
2) PHP 5.5
3) PHP 5.6
4) PHP 5.3
(default is 1): 2
Do you want to set up SSH for your instances?
(y/n): n

```

