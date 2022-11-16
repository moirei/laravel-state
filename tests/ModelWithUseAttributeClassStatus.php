<?php

namespace MOIREI\State\Tests;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property TestEnum $status
 */
class ModelWithUseAttributeClassStatus extends Model
{
    protected function status(): Attribute
    {
        return TestState::useAttribute();
    }
}
