<?php

namespace MOIREI\State\Tests;

use Illuminate\Database\Eloquent\Model;

/**
 * @property TestEnum $status
 */
class ModelWithSimpleClassStatus extends Model
{
    protected $casts = [
        'status' => TestState::class,
    ];
}
