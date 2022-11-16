<?php

namespace MOIREI\State\Traits;

use MOIREI\State\Casts\AsEnumState;
use MOIREI\State\Helpers;

/**
 * @property array $casts
 */
trait CastsEnumAttributesState
{
    /**
     * Merge new casts with existing casts on the model.
     *
     * @param  array  $casts
     * @return $this
     */
    abstract public function mergeCasts($casts);

    /**
     * Replaces all enum casts with states
     */
    public function initializeCastsEnumAttributesState()
    {
        $mappedCasts = [];
        foreach ($this->casts as $attribute => $cast) {
            if (Helpers::isEnum($cast) && in_array(HasEnumState::class, class_uses($cast))) {
                $mappedCasts[$attribute] = AsEnumState::class.':'.$cast;
            }
        }
        $this->mergeCasts($mappedCasts);
    }
}
