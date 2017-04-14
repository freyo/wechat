<?php

namespace Stoneworld\Wechat;

use Stoneworld\Wechat\Utils\Bag;

/**
 * OAuth 成员登录授权获取用户信息.
 */
class Authorize
{
    const API_USER  = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_login_info';
    const API_URL   = 'https://qy.weixin.qq.com/cgi-bin/loginpage';
    const API_LOGIN = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_login_url';

    /**
     * 应用ID.
     *
     * @var string
     */
    protected $appId;
    /**
     * 应用secret.
     *
     * @var string
     */
    protected $appSecret;
    /**
     * Http对象
     *
     * @var Http
     */
    protected $http;
    /**
     * 输入.
     *
     * @var Bag
     */
    protected $input;

    /**
     * constructor.
     *
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->appId     = $appId;
        $this->appSecret = $appSecret;
        $this->http      = new Http(new AccessToken($appId, $appSecret));
        $this->input     = new Input();
    }

    /**
     * 通过授权获取用户.
     *
     * @param string $to
     * @param string $state
     * @param string $usertype
     *
     * @return array | null
     */
    public function authorize($to = null, $state = 'STATE', $usertype = 'all')
    {
        if (!$this->input->get('state') && !$this->input->get('auth_code')) {
            $this->redirect($to, $state, $usertype);
        }

        return $this->user();
    }

    /**
     * 直接跳转.
     *
     * @param string $to
     * @param string $state
     * @param string $usertype
     */
    public function redirect($to = null, $state = null, $usertype = null)
    {
        $state = $state ? $state : md5(time());
        header('Location:' . $this->url($to, $state, $usertype));

        exit;
    }

    /**
     * 生成outh URL.
     *
     * @param string $to
     * @param string $state
     * @param string $usertype
     *
     * @return string
     */
    public function url($to = null, $state = 'STATE', $usertype = 'all')
    {
        $to !== null || $to = Url::current();

        $params = array(
            'corp_id'      => $this->appId,
            'redirect_uri' => $to,
            'state'        => $state,
            'usertype'     => $usertype
        );

        return self::API_URL . '?' . http_build_query($params);
    }

    /**
     * 获取企业号登录用户信息.
     * @return array
     */
    public function user()
    {
        return $this->http->jsonPost(self::API_USER, array('auth_code' => $this->input->get('auth_code')));
    }

    /**
     * 获取登录企业号官网的url.
     *
     * @param string $login_ticket 通过get_login_info得到的login_ticket, 10小时有效
     * @param string $target       登录跳转到企业号后台的目标页面，目前有：agent_setting、send_msg、contact
     * @param string $agentid      授权方应用id
     *
     * @return string
     */
    public function getLoginUrl($login_ticket, $target, $agentid = null)
    {
        $params = array(
            'login_ticket' => $login_ticket,
            'target'       => $target,
            'agentid'      => $agentid
        );

        $response = $this->http->jsonPost(self::API_LOGIN, $params);

        return $response['login_url'];
    }
}
