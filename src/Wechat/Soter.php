<?php

namespace Stoneworld\Wechat;

use Stoneworld\Wechat\Utils\JSON;

/**
 * 微信 SOTER生物认证.
 */
class Soter
{
    const API_VERIFY = 'https://qyapi.weixin.qq.com/cgi-bin/soter/verify_signature';

    /**
     * Http对象
     *
     * @var Http
     */
    protected $http;

    /**
     * constructor.
     *
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->http = new Http(new AccessToken($appId, $appSecret));
    }

    /**
     * SOTER生物认证后台接口.
     *
     * @param string $openid         用户在企业号的openid
     * @param string $json_string    requireSoterBiometricAuthentication接口返回的result_json_string字段（注意该字段需要json转义）
     * @param string $json_signature requireSoterBiometricAuthentication接口返回的result_json_signature字段
     *
     * @return bool
     */
    public function verify($openid, $json_string, $json_signature)
    {
        $params = array(
            'openid'         => $openid,
            'json_string'    => $json_string,
            'json_signature' => $json_signature
        );

        $response = $this->http->jsonPost(self::API_VERIFY, $params);

        return $response['is_ok'] == 'ok' ? true : false;
    }
}
