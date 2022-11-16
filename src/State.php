<?php

namespace MOIREI\State;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MOIREI\State\Casts\AsClassState;
use MOIREI\State\Traits\HasStateInteractions;

abstract class State implements Castable
{
    use HasStateInteractions;

    public mixed $value;

    public function __construct(
        public Model $model,
        public string $attribute,
        public mixed $originalValue,
    ) {
        $value = Helpers::getStateValue(static::class, $originalValue, $model, $model->getAttributes());
        $this->setValue($value);
    }

    /**
     * Transition current state to a new state.
     *
     * @param  mixed  $state
     * @return static
     */
    public function transitionTo($state)
    {
        $this->model->{$this->attribute} = $state;
        // return new static($this->model, $this->attribute, $this->value);
        $this->setValue($state);

        return $this;
    }

    /**
     * Get the state value.
     *
     * @return mixed
     */
    public function value()
    {
        $stateEntity = Helpers::getStateEntity(static::class, $this->model, $this->value, $this->model->getAttributes());
        $value = $stateEntity ? $stateEntity->state : $this->value;

        return Helpers::cast($value, $this->type());
    }

    public function __toString()
    {
        return Helpers::toString($this->value);
    }

    /**
     * Set the object value.
     *
     * @param  mixed  $value
     */
    public function setValue($value)
    {
        $this->value = Helpers::rawValue($value);
    }

    /**
     * Get the name of the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return string
     * @return string|\Illuminate\Contracts\Database\Eloquent\CastsAttributes|\Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes
     */
    public static function castUsing(array $arguments)
    {
        return new AsClassState(static::class);
    }

    /**
     * Use the state as a model attribute.
     *
     * @return Attribute
     */
    public static function useAttribute(): Attribute
    {
        [$model, $attribute] = Helpers::getCallingModel();
        /** @var CastsAttributes */
        $caster = static::castUsing([]);

        return Attribute::make(
            get: fn ($value, $attributes) => $caster->get($model, $attribute, $value, $attributes),
            set: fn ($value, $attributes) => $caster->set($model, $attribute, $value, $attributes),
        );
    }

    /**
     * Make a new state dynamically.
     *
     * @param  StateEntity[]  $states
     * @param  string  $type
     * @param  mixed  $default
     * @return Attribute
     */
    public static function make(array $states, string $type = 'string', $default = null): Attribute
    {
        $className = 'DynamicState_' . Str::random() . '_' . time() . '';
        $baseClass = DynamicState::class;

        eval("final class $className extends $baseClass{}");

        $className::setStateProps([
            'states' => $states,
            'type' => $type,
            'default' => $default,
        ]);

        return $className::useAttribute();
    }

    /**
     * Create a new StateEntity.
     *
     * @param  mixed  $state
     * @param  mixed  $next
     * @param  callable  $get
     * @param  callable  $set
     * @param  mixed  $before
     * @param  mixed  $after
     * @return StateEntity
     */
    public static function on(
        mixed $state,
        mixed $next = null,
        callable $get = null,
        callable $set = null,
        $before = null,
        $after = null,
    ): StateEntity {
        return StateEntity::make(
            state: $state,
            next: $next,
            get: $get,
            set: $set,
            before: $before,
            after: $after,
        );
    }
}
