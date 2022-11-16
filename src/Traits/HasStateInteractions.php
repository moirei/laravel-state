<?php

namespace MOIREI\State\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use MOIREI\State\Helpers;
use MOIREI\State\StateEntity;

/**
 * @property mixed $value
 */
trait HasStateInteractions
{
    /**
     * Get the state value.
     *
     * @return mixed
     */
    abstract public function value();

    /**
     * Transition current state to a new state.
     *
     * @param  mixed  $state
     * @return static
     */
    abstract public function transitionTo($state);

    /**
     * Get state conditions.
     *
     * @return StateEntity[]
     */
    abstract public static function states();

    /**
     * Use the target enum as a model attribute.
     *
     * @return Attribute
     */
    abstract public static function useAttribute(): Attribute;

    /**
     * Get default state.
     *
     * @return mixed
     */
    public static function default()
    {
        return null;
    }

    /**
     * Get the state type for casting.
     *
     * @return string
     */
    public static function type()
    {
        return 'string';
    }

    /**
     * Alias of transitionTo.
     * Transition current state to a new state.
     *
     * @param  mixed  $state
     * @return static
     */
    public function markAs($state)
    {
        return $this->transitionTo($state);
    }

    /**
     * Check state.
     *
     * @param  mixed  $state
     * @return bool
     */
    public function is($state)
    {
        return Helpers::equals($state, $this->value);
    }

    /**
     * Get the next states.
     *
     * @return array
     */
    public function next(): array
    {
        $stateEntity = Helpers::getValueStateEntity(static::class, $this->value, false, false);
        if (!$stateEntity) {
            return [];
        }
        $nextStates = $stateEntity->next();
        $type = $this->type();

        if (in_array('*', $nextStates)) {
            $nextStates = collect($nextStates)
                ->filer(fn (StateEntity $stateEntity) => !Helpers::equals($stateEntity->state, $this->value));
        }

        return collect($nextStates)
            ->map(fn ($nextState) => Helpers::cast(Helpers::rawValue($nextState), $type))
            ->values()
            ->toArray();
    }

    /**
     * Check if state can be transition to the provided state.
     *
     * @param  mixed  $state
     * @return bool
     */
    public function canTransitionTo($state): bool
    {
        $nextStates = $this->next();
        $exists = collect($nextStates)
            ->filter(fn ($nextState) => Helpers::equals($nextState, $state))
            ->count();

        return (bool) (!!$exists);
    }

    /**
     * Alias of canTransitionTo.
     * Check if state can be transition to the provided state.
     *
     * @param  mixed  $state
     * @return bool
     */
    public function canBe($state): bool
    {
        return $this->canTransitionTo($state);
    }
}
