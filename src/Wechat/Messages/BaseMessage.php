<?php

namespace Stoneworld\Wechat\Messages;

use Stoneworld\Wechat\Utils\MagicAttributes;
use Stoneworld\Wechat\Utils\XML;

/**
 * 消息基类.
 *
 * @property string      $from
 * @property string      $to
 * @property string      $staff
 *
 * @method BaseMessage to($to)
 * @method BaseMessage from($from)
 * @method BaseMessage staff($staff)
 * @method array       toStaff()
 * @method array       toReply()
 * @method array       toBroadcast()
 */
abstract class BaseMessage extends MagicAttributes
{
    /**
     * 允许的属性.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * 基础属性.
     *
     * @var array
     */
    protected $baseProperties = [
                                 'from',
                                 'to',
                                 'to_group',
                                 'to_all',
                                 'staff',
                                ];

    /**
     * 生成用于主动推送的数据.
     *
     * @return array
     */
    public function buildForStaff()
    {
        if (!method_exists($this, 'toStaff')) {
            throw new \Exception(__CLASS__.'未实现此方法：toStaff()');
        }

        $base = [
                 'touser'  => $this->to,
                 'msgtype' => $this->getDefaultMessageType(),
                ];
        if (!empty($this->staff)) {
            $base['customservice'] = ['kf_account' => $this->staff];
        }

        return array_merge($base, $this->toStaff());
    }

    /**
     * 生成用于回复的数据.
     *
     * @return array
     */
    public function buildForReply()
    {
        if (!method_exists($this, 'toReply')) {
            throw new \Exception(__CLASS__.'未实现此方法：toReply()');
        }

        $base = [
                 'ToUserName'   => $this->to,
                 'FromUserName' => $this->from,
                 'CreateTime'   => time(),
                 'MsgType'      => $this->getDefaultMessageType(),
                ];

        return XML::build(array_merge($base, $this->toReply()));
    }

    /**
     * 生成通过群发的数据.
     *
     * @return array
     */
    public function buildForBroadcast()
    {
        if (!method_exists($this, 'toStaff')) {
            throw new \Exception(__CLASS__.'未实现此方法：toStaff()');
        }
        $group = [
                'touser'  => $this->touser,
                'toparty' => $this->toparty,
                'totag'   => $this->totag,
                'agentid' => $this->agentid,
            ];
        $base = [
            'safe'    => 0,
            'msgtype' => $this->getDefaultMessageType(),
        ];

        return array_merge($group, $this->toStaff(), $base);
    }

    /**
     * 生成通过群发预览的数据.
     *
     * @param $type
     *
     * @throws \Exception
     *
     * @return array
     */
    public function buildForBroadcastPreview($type)
    {
        if (!method_exists($this, 'toStaff')) {
            throw new \Exception(__CLASS__.'未实现此方法：toStaff()');
        }

        $base = [
            $type     => $this->to,
            'msgtype' => $this->getDefaultMessageType(),
        ];

        return array_merge($base, $this->toStaff());
    }

    /**
     * 获取默认的消息类型名称.
     *
     * @return string
     */
    public function getDefaultMessageType()
    {
        $class = explode('\\', get_class($this));

        return strtolower(array_pop($class));
    }

    /**
     * 验证
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    protected function validate($attribute, $value)
    {
        $properties = array_merge($this->baseProperties, $this->properties);

        return in_array($attribute, $properties, true);
    }
}
