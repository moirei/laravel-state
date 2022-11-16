<?php

namespace MOIREI\State\Tests;

use Illuminate\Database\Eloquent\Model;
use MOIREI\State\Traits\CastsEnumAttributesState;

/**
 * @property TestEnum $status
 */
class ModelWithSimpleEnumStatus extends Model
{
    use CastsEnumAttributesState;

    protected $casts = [
        'status' => TestEnum::class,
    ];
}
