<?php

namespace MOIREI\State\Tests\Database;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property ClassPostMulticastStatus $status
 */
class Post_MulticastClass extends Post
{
    protected function status(): Attribute
    {
        return ClassPostMulticastStatus::useAttribute();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return new Post_MulticastClassFactory;
    }
}
