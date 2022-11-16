<?php

use MOIREI\State\Exceptions\IllegalStateTransition;
use MOIREI\State\Helpers;
use MOIREI\State\Tests\TestEnum;
use MOIREI\State\Tests\TestState;

uses()->group('helpers', 'helpers-transition-to');

it('should transition to next allowed state on enum', function () {
    $from = TestEnum::ONE;
    $to = TestEnum::TWO;
    $fn = fn () => 1;

    $result = Helpers::transitionTo(TestEnum::class, $from, $to, $fn);

    expect($result)->toEqual(1);
});

it('should not transition to illegal state on enum', function () {
    $from = TestEnum::ONE;
    $to = TestEnum::THREE;
    $fn = fn () => 1;

    $this->expectException(IllegalStateTransition::class);

    $result = Helpers::transitionTo(TestEnum::class, $from, $to, $fn);

    expect($result)->toBeNull();
});

it('should transition to next allowed state on class state', function () {
    $from = TestState::ONE;
    $to = TestState::TWO;
    $fn = fn () => 1;

    $result = Helpers::transitionTo(TestState::class, $from, $to, $fn);

    expect($result)->toEqual(1);
});

it('should not transition to illegal state on class state', function () {
    $from = TestState::ONE;
    $to = TestState::THREE;
    $fn = fn () => 1;

    $this->expectException(IllegalStateTransition::class);

    $result = Helpers::transitionTo(TestState::class, $from, $to, $fn);

    expect($result)->toBeNull();
});
