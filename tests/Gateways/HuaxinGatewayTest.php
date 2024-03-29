<?php

/*
 * This file is part of the overtrue/easy-sms.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zzy\LaravelSms\Tests\Gateways;

use Zzy\LaravelSms\Exceptions\GatewayErrorException;
use Zzy\LaravelSms\Gateways\HuaxinGateway;
use Zzy\LaravelSms\Message;
use Zzy\LaravelSms\PhoneNumber;
use Zzy\LaravelSms\Support\Config;
use Zzy\LaravelSms\Tests\TestCase;

class HuaxinGatewayTest extends TestCase
{
    public function testSend()
    {
        $config = [
            'user_id' => 'mock-user-id',
            'password' => 'mock-password',
            'account' => 'mock-account',
            'ip' => '127.0.0.1',
            'ext_no' => '',
        ];
        $gateway = \Mockery::mock(HuaxinGateway::class.'[post]', [$config])->shouldAllowMockingProtectedMethods();

        $gateway->shouldReceive('post')->with('http://127.0.0.1/smsJson.aspx', [
            'userid' => 'mock-user-id',
            'password' => 'mock-password',
            'account' => 'mock-account',
            'mobile' => 18188888888,
            'content' => 'This is a test message.',
            'sendTime' => '',
            'action' => 'send',
            'extno' => '',
        ])->andReturn([
            'returnstatus' => 'Success',
            'message' => '操作成功',
            'remainpoint' => '100',
            'taskID' => '1504080852350206',
            'successCounts' => '1',
        ], [
            'returnstatus' => 'Faild',
            'message' => '操作失败',
            'remainpoint' => '0',
            'taskID' => '0',
            'successCounts' => '0',
        ])->times(2);

        $message = new Message(['content' => 'This is a test message.']);
        $config = new Config($config);
        $this->assertSame(
            [
                'returnstatus' => 'Success',
                'message' => '操作成功',
                'remainpoint' => '100',
                'taskID' => '1504080852350206',
                'successCounts' => '1',
            ],
            $gateway->send(new PhoneNumber(18188888888), $message, $config)
        );

        $this->expectException(GatewayErrorException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('操作失败');

        $gateway->send(new PhoneNumber(18188888888), $message, $config);
    }
}
