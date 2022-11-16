<?php

use MOIREI\State\Helpers;
use MOIREI\State\StateEntity;
use MOIREI\State\Tests\TestEnum;
use MOIREI\State\Tests\TestState;

uses()->group('helpers', 'helpers-get-states');

it('should states from enum class', function () {
    $states = Helpers::getStates(TestEnum::class);

    expect($states)->toBeArray();
    expect($states[0])->toBeInstanceOf(StateEntity::class);
});

it('should states from enum object', function () {
    $states = Helpers::getStates(TestEnum::ONE);

    expect($states)->toBeArray();
    expect($states[0])->toBeInstanceOf(StateEntity::class);
});

it('should states from state class', function () {
    $states = Helpers::getStates(TestState::class);

    expect($states)->toBeArray();
    expect($states[0])->toBeInstanceOf(StateEntity::class);
});

it('should states from state object', function () {
    $state = new class extends TestState
    {
        public function __construct()
        {
            //
        }
    };

    $states = Helpers::getStates($state);

    expect($states)->toBeArray();
    expect($states[0])->toBeInstanceOf(StateEntity::class);
});
