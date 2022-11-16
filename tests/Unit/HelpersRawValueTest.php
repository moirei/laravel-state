<?php

use MOIREI\State\Helpers;
use MOIREI\State\Tests\TestEnum;

uses()->group('helpers', 'helpers-raw-value');

it('should get the raw value of an enum', function () {
    $value = Helpers::rawValue(TestEnum::ONE);

    expect($value)->toBeString();
    expect($value)->toEqual(TestEnum::ONE->value);
});

it('should get the raw value of a string', function () {
    $value = Helpers::rawValue('1');

    expect($value)->toBeString();
    expect($value)->toEqual('1');
});

it('should get the raw value of an integer', function () {
    $value = Helpers::rawValue(1);

    expect($value)->toBeInt();
    expect($value)->toEqual(1);
});
