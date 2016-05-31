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

3. Amazon Paymentsの基本的な設定

merchant_id, access_key, secret_key, client_idの設定をしましょう。

自分のキーがわからない場合は [Seller Central で開発キーの確認](https://github.com/johna1203/pwa-aws-summit/wiki/Seller-Central-%E3%81%A7%E9%96%8B%E7%99%BA%E3%82%AD%E3%83%BC%E3%81%AE%E7%A2%BA%E8%AA%8D)をみてください。

config/autoloadの中に、 local.php.dist ファイルを用意してあるので、それを local.php に変更して中身を自分の設定に変更しましょう。

```shell

$ cp local.php.dist local.php
$ vi local.php #好きな、エディターを使って編集してね

<?php

return [
    'pwaConfig' => [
        'merchant_id'   => 'A23YM23UEBY8FM',
        'access_key'    => 'AKIAJ....N5GV.....YMQ',
        'secret_key'    => 'QEn.....5YBJdZoW......O2wK',
        'client_id'     => 'amzn1.application-oa2-client.eef53cd200af4140be574e1a44e9576c',
        'currency_code' => 'JPY',
        'region'        => 'jp',
        'sandbox'   => true
    ]
];
```

これで、アプリケーションの作成準備ができました。

2. EB CLIの設定

EB CLI 基本的な設定は、[.elasticbeanstalk/config.global.yml](https://raw.githubusercontent.com/johna1203/pwa-aws-summit/master/pwa-php-zend-expressive-sample/.elasticbeanstalk/config.global.yml)
で設定してありますので変更が必要なければこのまま使っても問題ないと思います。

下記のようになっております。必要に応じて変更してください。

```yml
branch-defaults:
  default:
    environment: null
global:
  application_name: pwa-php-zend-expressive-sample
  default_ec2_keyname: null
  default_platform: PHP 5.5
  default_region: ap-northeast-1
  profile: eb-cli
  sc: null
```

3. eb create でアプリケーションを作成してみよう！

今回は、amzn-paymentsと言うEnvironmentを使ってアプリケーションを作成します。

コマンドはこんな感じです。

```shell
$ eb create amzn-payments
Creating application version archive "app-160531_203116".
Uploading pwa-php-zend-expressive-sample/app-160531_203116.zip to S3. This may take a while.
Upload Complete.
Environment details for: amzn-payments
  Application name: pwa-php-zend-expressive-sample
  Region: ap-northeast-1
  Deployed Version: app-160531_203116
  Environment ID: e-kymrtdw6qg
  Platform: 64bit Amazon Linux 2016.03 v2.1.2 running PHP 5.5
  Tier: WebServer-Standard
  CNAME: UNKNOWN
  Updated: 2016-05-31 11:31:19.118000+00:00
Printing Status:

......省略

INFO: Successfully launched environment: amzn-payments

```

以上で一応アプリケーションは起動できたはずです。

確認してみよう。
eb open コマンドを使えばブラウザーでアプリケーションを開く事ができます。

```shell

$ eb open amzn-payments

```

このような画面がでれば、第一段階は成功です。

![サンプルプログラム](https://raw.githubusercontent.com/wiki/johna1203/pwa-aws-summit/images/php-app-top-sample.png)

3. オレオレSSLの作成