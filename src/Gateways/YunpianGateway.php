<?php

/*
 * This file is part of the overtrue/easy-sms.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zzy\LaravelSms\Gateways;

use Zzy\LaravelSms\Contracts\MessageInterface;
use Zzy\LaravelSms\Contracts\PhoneNumberInterface;
use Zzy\LaravelSms\Exceptions\GatewayErrorException;
use Zzy\LaravelSms\Support\Config;
use Zzy\LaravelSms\Traits\HasHttpRequest;

/**
 * Class YunpianGateway.
 *
 * @see https://www.yunpian.com/doc/zh_CN/intl/single_send.html
 */
class YunpianGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_TEMPLATE = 'https://%s.yunpian.com/%s/%s/%s.%s';

    const ENDPOINT_VERSION = 'v2';

    const ENDPOINT_FORMAT = 'json';

    /**
     * @param \Zzy\LaravelSms\Contracts\PhoneNumberInterface $to
     * @param \Zzy\LaravelSms\Contracts\MessageInterface     $message
     * @param \Zzy\LaravelSms\Support\Config                 $config
     *
     * @return array
     *
     * @throws \Zzy\LaravelSms\Exceptions\GatewayErrorException ;
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $endpoint = $this->buildEndpoint('sms', 'sms', 'single_send');

        $signature = $config->get('signature', '');

        $content = $message->getContent($this);

        $result = $this->request('post', $endpoint, [
            'form_params' => [
                'apikey' => $config->get('api_key'),
                'mobile' => $to->getUniversalNumber(),
                'text' => 0 === \stripos($content, '【') ? $content : $signature.$content,
            ],
            'exceptions' => false,
        ]);

        if ($result['code']) {
            throw new GatewayErrorException($result['msg'], $result['code'], $result);
        }

        return $result;
    }

    /**
     * Build endpoint url.
     *
     * @param string $type
     * @param string $resource
     * @param string $function
     *
     * @return string
     */
    protected function buildEndpoint($type, $resource, $function)
    {
        return sprintf(self::ENDPOINT_TEMPLATE, $type, self::ENDPOINT_VERSION, $resource, $function, self::ENDPOINT_FORMAT);
    }
}
