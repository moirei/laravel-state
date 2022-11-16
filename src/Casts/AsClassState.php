<?php

namespace MOIREI\State\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use MOIREI\State\Helpers;
use MOIREI\State\State;

final class AsClassState implements CastsAttributes
{
    /**
     * The state class.
     *
     * @var string
     */
    protected $stateClass;

    /**
     * Create a new cast class instance.
     *
     * @param  sstateClass  $stateClass
     * @return void
     */
    public function __construct(string $stateClass)
    {
        $this->stateClass = $stateClass;
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        $stateClass = $this->stateClass;

        /** @var State */
        $instance = Helpers::getCache([spl_object_id($model), $key, 'class']);

        if ($instance) {
            $stateValue = Helpers::getStateValue($this->stateClass, $value, $model, $attributes);
            $instance->originalValue = $stateValue;
            $instance->setValue($stateValue);
        } else {
            $instance = new $stateClass($model, $key, $value, $attributes);
            Helpers::setCache([spl_object_id($model), $key, 'class'], $instance);
        }

        return $instance;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  State  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        /** @var State */
        $previousInstance = Helpers::getCache([spl_object_id($model), $key, 'class']);
        $value = Helpers::rawValue($value);

        $stateValue = Helpers::getStateSetterValue($this->stateClass, $value, $model, $attributes);
        $setValue = is_array($stateValue) ? $stateValue : [$key => $stateValue];

        if (!$previousInstance) {
            // this is not a transition
            return $setValue;
        }

        return Helpers::transitionTo(
            $this->stateClass,
            $previousInstance->value,
            $value,
            function () use ($setValue) {
                return $setValue;
            },
            []
        );
    }
}
