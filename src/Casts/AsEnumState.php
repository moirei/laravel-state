<?php

namespace MOIREI\State\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use MOIREI\State\Helpers;

final class AsEnumState implements CastsAttributes
{
    /**
     * The enum class.
     *
     * @var string
     */
    protected $enumClass;

    /**
     * Create a new cast class instance.
     *
     * @param  senumClass  $enumClass
     * @return void
     */
    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
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
        $enumClass = $this->enumClass;
        $stateValue = Helpers::getStateValue($enumClass, $value, $model, $attributes);
        $enum = $enumClass::from(Helpers::rawValue($stateValue));

        // cache the enum values (globally)
        // this is limitation work-around as Enums currently can't hold dynamic properties
        Helpers::setCache([spl_object_id($model), $key, 'enum'], $enum);
        Helpers::setCache([spl_object_id($enum), 'value'], $enum->value);
        Helpers::setCache([spl_object_id($enum), 'model'], $model);
        Helpers::setCache([spl_object_id($enum), 'attribute'], $key);

        return $enum;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        /** @var \MOIREI\State\Traits\HasStateInteractions */
        $previousEnum = Helpers::getCache([spl_object_id($model), $key, 'enum']);
        $stateValue = Helpers::getStateSetterValue($this->enumClass, $value, $model, $attributes);
        $setValue = is_array($stateValue) ? $stateValue : [$key => $stateValue];

        if (!$previousEnum) {
            // this is not a transition
            return $setValue;
        }

        return Helpers::transitionTo($this->enumClass, $previousEnum, $value, function () use ($previousEnum, $value, $setValue) {
            if (!Helpers::equals($previousEnum->value(), $value)) {
                // update the enum cache value if not up to date
                Helpers::setCache([spl_object_id($previousEnum), 'value'], Helpers::rawValue($value));
            }

            return $setValue;
        }, []);
    }
}
