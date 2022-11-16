<?php

namespace MOIREI\State;

use Illuminate\Support\Arr;

abstract class DynamicState extends State
{
    protected static array $dynamicStates = [];

    /**
     * Get the state type for casting.
     *
     * @return string
     */
    public static function type()
    {
        return static::getStateProp('type', 'string');
    }

    /**
     * Get default state.
     *
     * @return mixed
     */
    public static function default()
    {
        return static::getStateProp('default');
    }

    /**
     * Get state conditions.
     *
     * @return StateEntity[]
     */
    public static function states()
    {
        return static::getStateProp('states', []);
    }

    protected static function setStateProps(array $props)
    {
        return Arr::set(static::$dynamicStates, static::class, $props);
    }

    protected static function getStateProp($field, $default = null)
    {
        return Arr::get(static::$dynamicStates, static::class.".$field", $default);
    }
}
