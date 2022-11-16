<?php

use Illuminate\Database\Eloquent\Casts\Attribute;
use MOIREI\State\State;
use MOIREI\State\Tests\TestEnum;
use MOIREI\State\Traits\HasStateInteractions;

uses()->group('has-state-transitions');

beforeEach(function () {
    $this->instance = new class
    {
        use HasStateInteractions;

        public $value = 'one';

        public function value()
        {
            return $this->value;
        }

        public function transitionTo($state)
        {
            //
        }

        public static function states()
        {
            return [
                State::on('one', 'two'),
                State::on('two', ['three', 'four']),
            ];
        }

        public static function useAttribute(): Attribute
        {
            return Attribute::make();
        }
        //
    };
});

it('should get next states [1]', function () {
    /** @var HasStateInteractions */
    $instance = $this->instance;

    $next = $instance->next();

    expect($next)->toHaveCount(1);
    expect($next)->toContain('two');
    expect($instance->canTransitionTo('two'))->toBeTrue();
    expect($instance->canTransitionTo('three'))->toBeFalse();
});

it('should get next states [2]', function () {
    /** @var HasStateInteractions */
    $instance = $this->instance;
    $instance->value = 'two';

    $next = $instance->next();

    expect($next)->toHaveCount(2);
    expect($next)->toContain('three');
    expect($next)->toContain('four');
    expect($instance->canTransitionTo('three'))->toBeTrue();
    expect($instance->canTransitionTo('four'))->toBeTrue();
    expect($instance->canTransitionTo('one'))->toBeFalse();
});

it('should get next states [3]', function () {
    /** @var HasStateInteractions */
    $instance = $this->instance;
    $instance->value = 'three';

    $next = $instance->next();

    expect($next)->toBeEmpty();
    expect($instance->canTransitionTo('one'))->toBeFalse();
    expect($instance->canTransitionTo('two'))->toBeFalse();
    expect($instance->canTransitionTo('four'))->toBeFalse();
});
