<?php

namespace MOIREI\State\Tests\Database;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property EnumPostMulticastStatus $status
 */
class Post_MulticastEnum extends Post
{
    protected function status(): Attribute
    {
        return EnumPostMulticastStatus::useAttribute();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return new Post_MulticastEnumFactory;
    }
}
