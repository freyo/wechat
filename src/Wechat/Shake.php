<?php

namespace Stoneworld\Wechat;

/**
 * 微信 摇一摇周边.
 */
class Shake
{
    const API_GET = 'https://qyapi.weixin.qq.com/cgi-bin/shakearound/getshakeinfo';

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
     * 获取设备及用户信息.
     *
     * @param string $ticket 摇周边业务的ticket，可在摇到的URL中得到，ticket生效时间为30分钟，每一次摇都会重新生成新的ticket
     *
     * @return bool
     */
    public function verify($ticket)
    {
        $params = [
            'ticket' => $ticket,
        ];

        $response = $this->http->jsonPost(self::API_GET, $params);

        return $response['data'];
    }
}
