<?php

namespace Stoneworld\Wechat\Messages;

/**
 * 文本消息.
 *
 * @property string $content
 */
class Text extends BaseMessage
{
    /**
     * 属性.
     *
     * @var array
     */
    protected $properties = ['content'];

    /**
     * 生成主动消息数组.
     *
     * @return array
     */
    public function toStaff()
    {
        return [
                'text' => [
                           'content' => $this->content,
                          ],
               ];
    }

    /**
     * 生成回复消息数组.
     *
     * @return array
     */
    public function toReply()
    {
        return [
                'Content' => $this->content,
               ];
    }
}
