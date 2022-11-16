<?php

namespace MOIREI\State;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use MOIREI\State\Exceptions\IllegalStateTransition;
use MOIREI\State\Exceptions\InvalidStateSet;
use MOIREI\State\Traits\HasStateInteractions;

class Helpers
{
    private static $cache = [];

    /**
     * Cast the value to the given type.
     *
     * @param  mixed  $value
     * @param  string  $type
     * @return mixed
     */
    public static function cast($value, string $type): mixed
    {
        if (enum_exists($type)) {
            if (static::isEnum($value)) {
                $value = $value->value;
            }

            return $type::from($value);
        } elseif ($type == 'string') {
            return (string) $value;
        } elseif ($type == 'integer') {
            return intval($value);
        } elseif ($type == 'float') {
            return floatval($value);
        } else {
            throw new \Exception("Cannot cast value to type [$type]");
        }
    }

    /**
     * Get the raw value of any value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function rawValue($value)
    {
        if (static::isEnum($value)) {
            return $value->value;
        }
        if ($value instanceof State) {
            return static::rawValue($value->value);
        }

        return $value;
    }

    /**
     * Check if value is an enum.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function isEnum($value): bool
    {
        if (is_string($value)) {
            return enum_exists($value);
        }

        return $value instanceof \UnitEnum;
    }

    /**
     * Assert two variables are equal.
     *
     * @param  mixed  $a
     * @param  mixed  $b
     * @return mixed
     */
    public static function equals($a, $b): bool
    {
        return static::rawValue($a) === static::rawValue($b);
    }

    /**
     * Used to resolve model and attribute when create a state for an attribute.
     * Get the calling model and relative attribute method.
     */
    public static function getCallingModel()
    {
        $stack = debug_backtrace();
        /** @var Model */
        $model = null;
        /** @var string */
        $attribute = null;
        for ($index = 1; $index < count($stack); $index++) {
            $object = Arr::get($stack, "$index.object");
            if (is_subclass_of($object, Model::class)) {
                $model = $object;
                $attribute = Arr::get($stack, "$index.function");
                break;
            }
        }
        if (is_null($model) || is_null($attribute)) {
            throw new \Exception('Cannot resolve calling model.');
        }

        return [$model, $attribute];
    }

    /**
     * Get the string value.
     *
     * @return mixed $value
     * @return string
     */
    public static function toString(mixed $value)
    {
        return (string) (static::rawValue($value));
    }

    /**
     * Get the defined states of an enum or state object
     *
     * @return mixed $target
     * @return StateEntity[]
     */
    public static function getStates($target): array
    {
        $class = is_string($target) ? $target : get_class($target);
        if (!method_exists($class, 'states')) {
            throw new InvalidStateSet($class);
        }

        return $class::states();
    }

    /**
     * Match values of needle array in haystack array.
     *
     * @param  array  $needle
     * @param  array  $haystack
     * @return bool
     */
    public static function match(array $needle, array $haystack)
    {
        $matched = true;
        foreach ($needle as $key => $value) {
            $haystackValue = Arr::get($haystack, $key);
            if (is_null($value) || is_bool($value) || is_numeric($value)) {
                $matched = $value === $haystackValue;
            } else {
                $matched = ((bool) $value) == ((bool) $haystackValue); // compare truthy values
            }
            if (!$matched) {
                break;
            }
        }

        return $matched;
    }

    /**
     * Assert that the state has state transitions.
     *
     * @param $state
     * @return StateEntity[]
     */
    public static function assertHasStates($state)
    {
        $states = static::getStates($state);
        if (empty($states)) {
            throw new \Exception('No states provided');
        }

        return $states;
    }

    /**
     * Get the initial state entity of a state.
     * Resolves from default if possible.
     *
     * @param  mixed  $state
     * @return StateEntity
     */
    public static function initialStateEntity($state): StateEntity
    {
        $states = static::assertHasStates($state);
        $class = is_string($state) ? $state : get_class($state);
        $default = $class::default();
        if (!is_null($default)) {
            $stateEntity = collect($states)->first(fn (StateEntity $stateEntity) => $stateEntity->is($default));
            if ($stateEntity) {
                return $stateEntity;
            }
        }

        return $states[0];
    }

    /**
     * Get the value of a state entity getter.
     *
     * @param  StateEntity  $stateEntity
     * @param  Model  $model
     * @param  array  $attributes
     * @return mixed
     */
    public static function getStateEntityValue(StateEntity $stateEntity, Model $model, array $attributes)
    {
        $getter = $stateEntity->getter();

        return $getter($stateEntity->state, $model, $attributes);
    }

    /**
     * Get the value of a state entity setter.
     *
     * @param  StateEntity  $stateEntity
     * @param  Model  $model
     * @param  array  $attributes
     * @return mixed
     */
    public static function setStateEntityValue(StateEntity $stateEntity, Model $model, array $attributes)
    {
        $setter = $stateEntity->setter();

        return $setter($stateEntity->state, $model, $attributes);
    }

    // ============================================================================
    // TODO: Below methods have no unit tests
    // ============================================================================

    /**
     * Get the state entity of a state object from value.
     *
     * @param  mixed  $state
     * @param  mixed  $value
     * @param  bool  $withDefault
     * @return StateEntity|null
     */
    public static function getValueStateEntity(
        $state,
        $value,
        bool $withDefault = false,
        bool $strict = true
    ) {
        $states = static::assertHasStates($state);

        $stateEntity = collect($states)->first(fn (StateEntity $stateEntity) => $stateEntity->is($value));

        if (!$stateEntity && $withDefault) {
            return static::initialStateEntity($state);
        } elseif (!$stateEntity && $strict) {
            throw new \Exception("No state entity defined for [$value]");
        }

        return $stateEntity;
    }

    /**
     * Get the state entity of a state object from value.
     *
     * @param  mixed  $state
     * @param  mixed  $value
     * @param  Model  $model
     * @param  array  $attributes
     */
    public static function getStateSetterValue($state, $value, Model $model, array $attributes)
    {
        $states = static::assertHasStates($state);

        /** @var StateEntity|null */
        $stateEntity = collect($states)->first(fn (StateEntity $stateEntity) => $stateEntity->is($value));

        if (!$stateEntity) {
            if (!is_null($value)) {
                return $value;
            }
            $stateEntity = static::initialStateEntity($state);
        }

        return Helpers::setStateEntityValue($stateEntity, $model, $attributes);
    }

    /**
     * Get the state from model value or attributes.
     *
     * @param  mixed  $state
     * @param  mixed  $value
     * @param  Model  $model
     * @param  array  $attributes
     */
    public static function getStateValue($state, $value, Model $model, array $attributes)
    {
        $stateEntity = static::getStateEntity($state, $model, $value, $attributes);

        if (!$stateEntity) {
            if (!is_null($value)) {
                return $value;
            }
            $stateEntity = static::initialStateEntity($state);
        }

        return $stateEntity->state;
    }

    /**
     * Resolve state entity from model state.
     *
     * @param  mixed  $state
     * @param  Model  $model
     * @param  mixed  $value
     * @param  array  $attributes
     * @return StateEntity|null
     */
    public static function getStateEntity(
        $state,
        Model $model,
        mixed $value,
        array $attributes,
    ) {
        $states = static::assertHasStates($state);

        return collect($states)->first(function (StateEntity $stateEntity) use ($model, $value, $attributes) {
            $stateEntityValue = static::getStateEntityValue($stateEntity, $model, $attributes);
            if (!is_bool($stateEntityValue)) {
                if (is_array($stateEntityValue)) {
                    return static::match($stateEntityValue, $attributes);
                }

                return static::equals($stateEntityValue, $value);
            }

            return $stateEntityValue;
        });
    }

    /**
     * Resolve state entity from model state or return the initial state entity.
     *
     * @param  mixed  $state
     * @param  Model  $model
     * @param  mixed  $value
     * @param  array  $attributes
     * @return StateEntity
     */
    public static function getStateEntityOrInitial(
        $state,
        Model $model,
        mixed $value,
        array $attributes,
    ): StateEntity {
        $stateEntity = static::getStateEntity($state, $model, $value, $attributes);

        if (!$stateEntity) {
            return static::initialStateEntity($state);
        }

        return $stateEntity;
    }

    /**
     * @param  HasStateInteractions|State  $state
     * @param  mixed  $fromState
     * @param  mixed  $toState
     * @param  callable  $transit
     * @param  mixed  $abortValue
     */
    public static function transitionTo($stateClass, $fromState, $toState, callable $transit = null, $abortValue = false)
    {
        $transit = $transit ?: fn () => null;

        if (Helpers::equals($fromState, $toState)) {
            return $transit($toState);
        }

        $fromStateEntity = Helpers::getValueStateEntity($stateClass, $fromState);

        $nextStates = $fromStateEntity->next();
        if (!in_array('*', $nextStates)) {
            $exists = (bool) collect($nextStates)
                ->filter(fn ($nextState) => static::equals($nextState, $toState))
                ->count();

            if (!$exists) {
                throw new IllegalStateTransition($stateClass, $fromState, $toState);
            }
        }

        $beforeHooks = $fromStateEntity->before();

        if (static::execAll($beforeHooks, $fromState, $toState) === false) {
            return $abortValue;
        }

        $transitionValue = $transit($toState);

        $afterHooks = $fromStateEntity->after();
        static::execAll($afterHooks);

        return $transitionValue;
    }

    /**
     * Execute an array of callables
     *
     * @param callable[] $callables
     * @param array ...$args
     */
    public static function execAll(array $callables, ...$args)
    {
        $responses = [];
        foreach ($callables as $callable) {
            $responses[] = $callable(...$args);
        }

        return array_filter($responses, function ($response) {
            return !is_null($response);
        });
    }

    public static function setCache($key, $value)
    {
        $key = is_array($key) ? implode('.', $key) : $key;
        Arr::set(static::$cache, $key, $value);

        return $value;
    }

    public static function clearCache($key)
    {
        $key = is_array($key) ? implode('.', $key) : $key;
        Arr::forget(static::$cache, $key);
    }

    public static function getCache($key, $default = null)
    {
        $key = is_array($key) ? implode('.', $key) : $key;

        return Arr::get(static::$cache, $key, $default);
    }
}
