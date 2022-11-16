<?php

use MOIREI\State\Helpers;
use MOIREI\State\StateEntity;
use MOIREI\State\Tests\TestState;

uses()->group('helpers', 'helpers-initial-state-entity');

it('should get initial state entity from first states items', function () {
    $state = new class extends TestState
    {
        public function __construct()
        {
            //
        }
    };
    $stateEntity = Helpers::initialStateEntity($state);

    expect($stateEntity)->toBeInstanceOf(StateEntity::class);
    expect($stateEntity->state)->toEqual(TestState::ONE);
});

it('should get initial state entity from default', function () {
    $state = new class extends TestState
    {
        public function __construct()
        {
            //
        }

        /**
         * Get default state.
         *
         * @return mixed
         */
        public static function default()
        {
            return TestState::TWO;
        }
    };
    $stateEntity = Helpers::initialStateEntity($state);

    expect($stateEntity)->toBeInstanceOf(StateEntity::class);
    expect($stateEntity->state)->toEqual(TestState::TWO);
});
