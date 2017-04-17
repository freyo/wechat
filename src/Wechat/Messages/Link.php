<?php

namespace Stoneworld\Wechat\Messages;

/**
 * 链接消息.
 *
 * @property string $content
 */
class Link extends BaseMessage
{
    /**
     * 属性.
     *
     * @var array
     */
    protected $properties = [
                             'title',
                             'description',
                             'url',
                            ];
}
