<?php

use MOIREI\State\Helpers;
use MOIREI\State\Tests\TestEnum;

uses()->group('helpers', 'helpers-to-string');

it('should get the string version of a string', function () {
    $value = Helpers::toString('1');

    expect($value)->toBeString();
    expect($value)->toEqual('1');
});

it('should get the string version of an integer', function () {
    $value = Helpers::toString(1);

    expect($value)->toBeString();
    expect($value)->toEqual('1');
});

it('should get the string version of an enum', function () {
    $value = Helpers::toString(TestEnum::ONE);

    expect($value)->toBeString();
    expect($value)->toEqual(TestEnum::ONE->value);
});
