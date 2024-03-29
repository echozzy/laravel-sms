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

use Zzy\LaravelSms\Gateways\TwilioGateway;
use Zzy\LaravelSms\Message;
use Zzy\LaravelSms\PhoneNumber;
use Zzy\LaravelSms\Support\Config;
use Zzy\LaravelSms\Tests\TestCase;

class TwilioGatewayTest extends TestCase
{
    public function testSend()
    {
        $config = [
            'account_sid' => 'mock-api-account-sid',
            'from' => 'mock-from',
            'token' => 'mock-token',
        ];
        $gateway = \Mockery::mock(TwilioGateway::class.'[request]', [$config])->shouldAllowMockingProtectedMethods();

        $gateway->shouldReceive('request')->andReturn([
            'status' => 'queued',
            'from' => 'mock-from',
            'to' => '+8618888888888',
            'body' => '【twilio】This is a test message.',
            'sid' => 'mock-api-account-sid',
            'error_code' => null,
        ]);

        $message = new Message(['content' => '【twilio】This is a test message.']);
        $config = new Config($config);

        $this->assertSame([
            'status' => 'queued',
            'from' => 'mock-from',
            'to' => '+8618888888888',
            'body' => '【twilio】This is a test message.',
            'sid' => 'mock-api-account-sid',
            'error_code' => null,
        ], $gateway->send(new PhoneNumber(18888888888, 86), $message, $config));
    }
}
