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
 * Class TianyiwuxianGateway.
 *
 * @author Darren Gao <realgaodacheng@gmail.com>
 */
class TianyiwuxianGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_TEMPLATE = 'http://jk.106api.cn/sms%s.aspx';

    const ENDPOINT_ENCODE = 'UTF8';

    const ENDPOINT_TYPE = 'send';

    const ENDPOINT_FORMAT = 'json';

    const SUCCESS_STATUS = 'success';

    const SUCCESS_CODE = '0';

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
        $endpoint = $this->buildEndpoint();

        $params = [
            'gwid' => $config->get('gwid'),
            'type' => self::ENDPOINT_TYPE,
            'rece' => self::ENDPOINT_FORMAT,
            'mobile' => $to->getNumber(),
            'message' => $message->getContent($this),
            'username' => $config->get('username'),
            'password' => strtoupper(md5($config->get('password'))),
        ];

        $result = $this->post($endpoint, $params);

        $result = json_decode($result, true);

        if (self::SUCCESS_STATUS !== $result['returnstatus'] || self::SUCCESS_CODE !== $result['code']) {
            throw new GatewayErrorException($result['remark'], $result['code']);
        }

        return $result;
    }

    /**
     * Build endpoint url.
     *
     * @return string
     */
    protected function buildEndpoint()
    {
        return sprintf(self::ENDPOINT_TEMPLATE, self::ENDPOINT_ENCODE);
    }
}
