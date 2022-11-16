<?php

namespace MOIREI\State;

use Illuminate\Support\Arr;

abstract class StateEntity
{
    public function __construct(
        public mixed $state,
        public array $attributes,
    ) {
        //
    }

    /**
     * Make a new StateEntity.
     *
     * @param  mixed  $state
     * @param  mixed  $next
     * @param  callable  $get
     * @param  callable  $set
     * @param  mixed  $before
     * @param  mixed  $after
     * @return StateEntity
     */
    public static function make(
        mixed $state,
        mixed $next = null,
        callable $get = null,
        callable $set = null,
        $before = null,
        $after = null,
    ): StateEntity {
        $attributes = [
            'next' => $next,
            'getter' => $get ?: fn ($value) => $value,
            'setter' => $set ?: fn ($value) => $value,
            'before' => $before,
            'after' => $after,
        ];

        return new class($state, $attributes) extends StateEntity
        {
            //
        };
    }

    /**
     * Check if state entity value is of state.
     *
     * @param  mixed  $value
     * @return bool
     */
    public function is($state): bool
    {
        return Helpers::equals($state, $this->state);
    }

    /**
     * Get next possible states.
     *
     * @return array
     */
    public function next()
    {
        $next = Arr::get($this->attributes, 'next');

        return $next ? Arr::wrap($next) : [];
    }

    /**
     * Get the getter function.
     *
     * @return callable
     */
    public function getter()
    {
        return Arr::get($this->attributes, 'getter');
    }

    /**
     * Get the setter function.
     *
     * @return callable
     */
    public function setter()
    {
        return Arr::get($this->attributes, 'setter');
    }

    /**
     * Get hooks executed before transitions.
     *
     * @return callable[]
     */
    public function before()
    {
        $hooks = Arr::get($this->attributes, 'before');

        return $hooks ? Arr::wrap($hooks) : [];
    }

    /**
     * Get hooks executed after transitions.
     *
     * @return callable[]
     */
    public function after()
    {
        $hooks = Arr::get($this->attributes, 'after');

        return $hooks ? Arr::wrap($hooks) : [];
    }
}
