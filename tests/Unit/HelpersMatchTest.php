<?php

use MOIREI\State\Helpers;

uses()->group('helpers', 'helpers-match');

it('should match empty arrays', function () {
    $array1 = [];
    $array2 = [];

    expect(Helpers::match($array1, $array2))->toBeTrue();
});

it('should match single boolean', function () {
    $array1 = ['a' => true];
    $array2 = ['a' => true, 'b' => false];

    expect(Helpers::match($array1, $array2))->toBeTrue();
});

it('expects present boolean of otherwise value to not match', function () {
    $array1 = ['a' => false];
    $array2 = ['a' => true, 'b' => false];

    expect(Helpers::match($array1, $array2))->toBeFalse();
});

it('should match single integers', function () {
    $array1 = ['a' => 1];
    $array2 = ['a' => 1, 'b' => 2];

    expect(Helpers::match($array1, $array2))->toBeTrue();
});

it('expects present integer of otherwise value to not match', function () {
    $array1 = ['a' => 1];
    $array2 = ['a' => 0, 'b' => 2];

    expect(Helpers::match($array1, $array2))->toBeFalse();
});

it('should match single null values', function () {
    $array1 = ['a' => null];
    $array2 = ['a' => null, 'b' => 2];

    expect(Helpers::match($array1, $array2))->toBeTrue();
});

it('expects present null value of otherwise value to not match', function () {
    $array1 = ['a' => null];
    $array2 = ['a' => 0, 'b' => 2];

    expect(Helpers::match($array1, $array2))->toBeFalse();
});

it('should match truthy values', function () {
    $array1 = ['a' => now()];
    $array2 = ['a' => now(), 'b' => 2];

    expect(Helpers::match($array1, $array2))->toBeTrue();
});

it('should match multiple values', function () {
    $array1 = [
        'a' => true,
        'b' => false,
        'c' => null,
    ];
    $array2 = [
        'a' => true,
        'b' => false,
        'c' => null,
        'd' => null,
    ];

    expect(Helpers::match($array1, $array2))->toBeTrue();
});
