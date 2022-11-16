<?php

namespace MOIREI\State\Tests\Database;

use MOIREI\State\Traits\CastsEnumAttributesState;

/**
 * @property EnumPostStatus $status
 */
class Post_CastedEnum extends Post
{
    use CastsEnumAttributesState;

    public function __construct($attributes = [])
    {
        $this->mergeCasts([
            'status' => EnumPostStatus::class,
        ]);
        parent::__construct($attributes);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return new Post_CastedEnumFactory;
    }
}
