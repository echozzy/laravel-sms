<?php
/** .-------------------------------------------------------------------
 * |      Site: www.zhouzy365.com
 * |      Date: 2019/11/1 上午11:13
 * |    Author: zzy <348858954@qq.com>
 * '-------------------------------------------------------------------*/
return [
    // HTTP 请求的超时时间（秒）
    'timeout' => 5.0,

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Zzy\LaravelSms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'aliyun'
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'aliyun' => [
            'access_key_id' => '',
            'access_key_secret' => '',
            'sign_name' => '',
            'gateway_name' => '阿里云',
            'template' => [
                'sms_code' => 'SMS_176910465',//模版标识和编号
                'sms_deliver_goods' => '',
            ]
        ],
        'juhe' => [
            'app_key' => '',
            'gateway_name' => '聚合数据',
        ],
        'baidu' => [
            'ak' => '',
            'sk' => '',
            'invoke_id' => '',
            'domain' => '',
            'gateway_name' => '百度云',
        ],
        'qcloud' => [
            'sdk_app_id' => '', // SDK APP ID
            'app_key' => '', // APP KEY
            'sign_name' => '', // 短信签名，如果使用默认签名，该字段可缺省（对应官方文档中的sign）
            'gateway_name' => '腾讯云 SMS',
        ],
    ],
];
