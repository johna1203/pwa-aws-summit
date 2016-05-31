# # Amazon Payments のサンプルを Elastic Beanstalk を使って動かしてみよう。

すぐに、アプリ開発を進めて頂けるように、[Zend Expressive](https://zendframework.github.io/zend-expressive/)
ベースにAmazon Paymentsの開発が始められるアプリケーションを作りました。

このアプリケーションを、Elastic Beanstalkを使って素早く立ち上げてみよう！ 最短１０分で！ ^^;

### 事前にこれらをインストールしておいてください

* [AWSCLI](https://aws.amazon.com/jp/cli/)
* [EB CLI](http://docs.aws.amazon.com/ja_jp/elasticbeanstalk/latest/dg/eb-cli3.html)

#では、アプリケーションの準備

1. [git clone をしよう](#user-content-git-cloneをしよう)
1. [Amazon Paymentsの基本的な設定](#user-content-amazon-paymentsの基本的な設定)
1. [localhostで動かしてみよう](#user-content-localhostで動かしてみよう)
1. [EB CLIの設定](#user-content-eb-cliの設定)
1. [eb create でアプリケーションを作成してみよう！](#user-content-eb-create-でアプリケーションを作成してみよう)
1. [HTTPSの環境を作ろう](#user-content-httpsの環境を作ろう)
1. [eb deploy して HTTPSの設定を反映させましょう](#user-content-eb-deploy-してhttpsの設定を反映させましょう)
1. [最後にアプリケーションのURLをSeller Centralに登録しよう](#user-content-最後にアプリケーションのurlをseller-centralに登録しよう)

## git clone をしよう

```shell

$ git clone https://github.com/johna1203/pwa-aws-summit.git
Cloning into 'pwa-aws-summit'...
remote: Counting objects: 72, done.
remote: Compressing objects: 100% (51/51), done.
remote: Total 72 (delta 3), reused 69 (delta 3), pack-reused 0
Unpacking objects: 100% (72/72), done.
Checking connectivity... done.

```

## Amazon Paymentsの基本的な設定

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

## localhostで動かしてみよう
Elastic Beanstalkに、deployする前にアプリケーションがうまく動作しているかlocalhostで確認する事ができます。

[Composer](https://getcomposer.org/) を使ってlocalhostで立ち上げるには下記のコマンドを実行してください。

```shell
$ composer install
$ composer serve

php -S 0.0.0.0:8080 -t public/ public/index.php

```

これで、[http://localhost:8080/](http://localhost:8080/)でアプリケーションが動いていると思います。

## EB CLIの設定

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

## eb create でアプリケーションを作成してみよう！ 

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

## HTTPSの環境を作ろう
Amazon Paymentsは、HTTPSがないと動かないため Elastic Beanstalk のアプリケーションをHTTPSに対応させる必要があります。
そこで、自分のSSLのキーを作りましょう。

適当な場所へ移動して、下記のコマンドを実行

```shell
$ /path/to/pwa-aws-summit/pwa-php-zend-expressive-sample/data
$ openssl genrsa 2048 > server.key
$ openssl req -new -key server.key > server.csr

You are about to be asked to enter information that will be incorporated
into your certificate request.
What you are about to enter is what is called a Distinguished Name or a DN.
There are quite a few fields but you can leave some blank
For some fields there will be a default value,
If you enter '.', the field will be left blank.
-----
Country Name (2 letter code) [AU]:JP
State or Province Name (full name) [Some-State]:Tokyo-to
Locality Name (eg, city) []:Meguro-ku
Organization Name (eg, company) [Internet Widgits Pty Ltd]:Amazon Japan
Organizational Unit Name (eg, section) []:Amazon Payments
Common Name (e.g. server FQDN or YOUR name) []:amzn-payments.egma52bepp.ap-northeast-1.elasticbeanstalk.com
Email Address []:froeming@amazon.com

Please enter the following 'extra' attributes
to be sent with your certificate request
A challenge password []:
An optional company name []:

$ openssl x509 -days 3650 -req -signkey server.key < server.csr > server.crt

```

できたら、AWSにSSL証明書を登録して、 .ebextensions/securelistener.config.distを編集します。

 ```shell

$ aws iam upload-server-certificate \
  --server-certificate-name amazon-payments-beanstalk-x509 \
  --certificate-body file://server.crt \
  --private-key file://server.key

{
    "ServerCertificateMetadata": {
        "ServerCertificateId": "ASCAJA3RI4B2UVIUAWVY4",
        "ServerCertificateName": "amazon-payments-beanstalk-x509",
        "Expiration": "2026-05-29T14:16:58Z",
        "Path": "/",
        "Arn": "arn:aws:iam::##########:server-certificate/amazon-payments-beanstalk-x509", #ここをコピー
        "UploadDate": "2016-05-31T14:18:12.691Z"
    }
}

$ cd /path/to/pwa-aws-summit/pwa-php-zend-expressive-sample/.ebextensions
$ cp securelistener.config.dist securelistener.config
$ vi securelistener.config #SSLCertificateId に Arnをペースト

option_settings:
  aws:elb:listener:443:
    SSLCertificateId: arn:aws:iam::##########:server-certificate/amazon-payments-beanstalk-x509
    ListenerProtocol: HTTPS
    InstancePort: 80

```

これで、HTTPSの準備完了です。

## eb deploy してHTTPSの設定を反映させましょう。

```shell

eb deploy amzn-payments

Creating application version archive "app-160531_232532".
Uploading pwa-php-zend-expressive-sample/app-160531_232532.zip to S3. This may take a while.
Upload Complete.
INFO: Environment update is starting.
INFO: Environment health has transitioned from Ok to Info. Application update in progress (running for 21 seconds).
INFO: Created security group named: sg-71099315
INFO: Deploying new version to instance(s).
INFO: New application version was deployed to running EC2 instances.
INFO: Environment update completed successfully.

```

httpsでページにアクセスしてみると This Connection is Untrusted と表示されますが自分で証明書を発行した為です。
気にせずに、そのまま進めるとアプリケーションの画面が表示されます。

![https://raw.githubusercontent.com/wiki/johna1203/pwa-aws-summit/images/untrust.png](https://raw.githubusercontent.com/wiki/johna1203/pwa-aws-summit/images/untrust.png)

***これで、Elastic Beansの設定は完了です。*** お疲れ様でした。

## 最後にアプリケーションのURLをSeller Centralに登録しよう

Amazon Paymentsのログインをするには、サイトのURLを Seller Centralに登録する必要があります。
今回私がDeployしたURLは[https://amzn-payments.egma52bepp.ap-northeast-1.elasticbeanstalk.com/](https://amzn-payments.egma52bepp.ap-northeast-1.elasticbeanstalk.com/)です。

このURLをベースにSeller Centralに登録する方法を教えます。

下の画像の画像のように

Javascriptの種類 : https://amzn-payments.egma52bepp.ap-northeast-1.elasticbeanstalk.com/

リダイレクトURL : https://amzn-payments.egma52bepp.ap-northeast-1.elasticbeanstalk.com/callback

に設定してください。

![https://raw.githubusercontent.com/wiki/johna1203/pwa-aws-summit/images/seller_central_url_add.png](https://raw.githubusercontent.com/wiki/johna1203/pwa-aws-summit/images/seller_central_url_add.png)

設定が成功したら、アプリケーションの***Amazonアカウントでを支払い***を押して、テストユーザでログインすると下の画像のようにAddress WidgetsとWallet Widgetsが表示されます。

![https://raw.githubusercontent.com/wiki/johna1203/pwa-aws-summit/images/seller_central_url_add.png](https://raw.githubusercontent.com/wiki/johna1203/pwa-aws-summit/images/seller_central_url_add.png)