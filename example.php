<?php

require __DIR__.'autoload.php';

use Stoneworld\Wechat\Message;
use Stoneworld\Wechat\Server;

$options = [
            'token'         => 'stoneworld1992',   //填写应用接口的Token
            'encodingaeskey'=> 'o1wze3492xoUVIc9ccTLJczO3BQ5pLfiHcKwtDEdqM9', //填写加密用的EncodingAESKey
            'appid'         => 'wx8ac123b21f53d722',  //填写高级调用功能的appid
            'appsecret'     => '4ZDHIETJ6e0oENlEkRhYwzWPTrkLdXedKcPcRjCkgQkuHtQTJ12ZhWHESowrJqS9', //填写高级调用功能的密钥
            'agentid'       => '5', //应用的id
        ];
        $weObj = new Server($options);
        $weObj->on('event', function ($event) {
            return Message::make('text')->content(var_export($event, true));
        });
        echo $weObj->server(); //注意, 企业号与普通公众号不同，必须打开验证，不要注释掉
