<?php

namespace Stoneworld\Wechat\Messages;

/**
 * 图文项.
 */
class NewsItem extends BaseMessage
{
    /**
     * 属性.
     *
     * @var array
     */
    protected $properties = [
                             'title',
                             'description',
                             'pic_url',
                             'url',
                            ];
}
