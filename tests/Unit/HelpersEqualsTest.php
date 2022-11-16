<?php

use MOIREI\State\Helpers;
use MOIREI\State\Tests\TestEnum;

uses()->group('helpers', 'helpers-equals');

it('expects two strings to be equal', function () {
    $equals = Helpers::equals('1', '1');
    expect($equals)->toBeTrue();
});

it('expects two integers to be equal', function () {
    $equals = Helpers::equals(1, 1);
    expect($equals)->toBeTrue();
});

it('expects a string and an integer to not be equal', function () {
    $equals = Helpers::equals('1', 1);
    expect($equals)->toBeFalse();
});

it('expects two enums to be equal', function () {
    $equals = Helpers::equals(TestEnum::ONE, TestEnum::ONE);
    expect($equals)->toBeTrue();
});

it('expects enum to be equal with its raw value', function () {
    $equals = Helpers::equals(TestEnum::ONE->value, TestEnum::ONE);
    expect($equals)->toBeTrue();
});

it('expects two different enums not to be equal', function () {
    $equals = Helpers::equals(TestEnum::ONE, TestEnum::TWO);
    expect($equals)->toBeFalse();
});
