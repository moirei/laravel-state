<?php

namespace MOIREI\State\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use MOIREI\State\Casts\AsEnumState;
use MOIREI\State\Helpers;

trait HasEnumState
{
    use HasStateInteractions;

    /**
     * Get the enum type.
     *
     * @return mixed
     */
    public static function type()
    {
        return static::class;
    }

    /**
     * Get the enum value.
     *
     * @return mixed
     */
    public function value()
    {
        return Helpers::getCache([spl_object_id($this), 'value'], $this->value);
    }

    /**
     * Get the active state value.
     * This returns the up-to-date value for this enum.
     *
     * @return mixed
     */
    public function activeValue()
    {
        $model = Helpers::getCache([spl_object_id($this), 'model']);
        if (!$model) {
            throw new \Exception('Cannot get active value with a related Eloquent model.');
        }
        $stateEntity = Helpers::getStateEntityOrInitial(static::class, $model, $this->value, $model->getAttributes());

        return $stateEntity->state;
    }

    /**
     * Transition current state to a new state.
     *
     * @param  mixed  $state
     * @return static
     */
    public function transitionTo($state)
    {
        $model = Helpers::getCache([spl_object_id($this), 'model']);
        $attribute = Helpers::getCache([spl_object_id($this), 'attribute']);

        if ($withModel = ($model && $attribute)) {
            // trigger model
            $model->$attribute = $state;
        } else {
            // standalone use
            $result = Helpers::transitionTo(self::type(), $this, $state);
            if ($result === false) {
                return $this;
            }
        }

        $value = Helpers::rawValue($state);

        // Work-around $enum->value being readonly. ReflectionEnum proved futile
        Helpers::setCache([spl_object_id($this), 'value'], $value);
        $enum = static::from($value);

        if ($withModel) {
            // set the model relation for new enum
            Helpers::setCache([spl_object_id($enum), 'model'], $model);
            Helpers::setCache([spl_object_id($enum), 'attribute'], $attribute);
        }

        return $enum;
    }

    /**
     * Use the target enum as a model attribute.
     *
     * @return Attribute
     */
    public static function useAttribute(): Attribute
    {
        [$model, $key] = Helpers::getCallingModel();
        $caster = new AsEnumState(static::class);

        return Attribute::make(
            get: fn ($value, $attributes) => $caster->get($model, $key, $value, $attributes),
            set: fn ($value, $attributes) => $caster->set($model, $key, $value, $attributes),
        );
    }
}
