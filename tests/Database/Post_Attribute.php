<?php

namespace MOIREI\State\Tests\Database;

/**
 * @property EnumPostStatus $status
 */
class Post_Attribute extends Post
{
    protected $table = 'posts';

    protected function status()
    {
        return EnumPostStatus::useAttribute();
    }
}
