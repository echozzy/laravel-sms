# laravel-sms
基于overtrue/easy-sms扩展修改
## 安装组件
composer require echozzy/laravel-sms

php artisan vendor:publish --provider='Zzy\LaravelSms\SmsServiceProvider'

## 配置文件
config/sms.php

## 使用
```php
$sms = app('easysms');
$sms->send(17396908413, [
    'content'  => '您的验证码为: 6379',
    'template' => 'SMS_176910465',
    'data' => [
        'code' => 6379
    ],
]);
```
或者
```php
use Zzy\LaravelSms\EasySms;

$easySms = new EasySms(config('sms'));

$easySms->send(13188888888, [
    'content'  => '您的验证码为: 6379',
    'template' => 'SMS_001',
    'data' => [
        'code' => 6379
    ],
]);
```
## 短信内容

由于使用多网关发送，所以一条短信要支持多平台发送，每家的发送方式不一样，但是我们抽象定义了以下公用属性：

- `content` 文字内容，使用在像云片类似的以文字内容发送的平台
- `template` 模板 ID，使用在以模板ID来发送短信的平台
- `data`  模板变量，使用在以模板ID来发送短信的平台

所以，在使用过程中你可以根据所要使用的平台定义发送的内容。

```php
$easySms->send(13188888888, [
    'content'  => '您的验证码为: 6379',
    'template' => 'SMS_001',
    'data' => [
        'code' => 6379
    ],
]);
```

你也可以使用闭包来返回对应的值：

```php
$easySms->send(13188888888, [
    'content'  => function($gateway){
        return '您的验证码为: 6379';
    },
    'template' => function($gateway){
        return 'SMS_001';
    },
    'data' => function($gateway){
        return [
            'code' => 6379
        ];
    },
]);
```

你可以根据 `$gateway` 参数类型来判断返回值，例如：

```php
$easySms->send(13188888888, [
    'content'  => function($gateway){
        if ($gateway->getName() == 'yunpian') {
            return '云片专用验证码：1235';
        }
        return '您的验证码为: 6379';
    },
    'template' => function($gateway){
        if ($gateway->getName() == 'aliyun') {
            return 'TP2818';
        }
        return 'SMS_001';
    },
    'data' => function($gateway){
        return [
            'code' => 6379
        ];
    },
]);
```
其他具体方法请查看[overtrue/easy-sms](https://github.com/overtrue/easy-sms)
