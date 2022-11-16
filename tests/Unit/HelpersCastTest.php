<?php

use MOIREI\State\Helpers;
use MOIREI\State\Tests\TestEnum;

uses()->group('helpers', 'helpers-cast');

it('should cast to string', function () {
    $value1 = Helpers::cast('1', 'string');
    $value2 = Helpers::cast(1, 'string');
    $value3 = Helpers::cast(true, 'string');

    expect($value1)->toBeString();
    expect($value1)->toEqual('1');

    expect($value2)->toBeString();
    expect($value2)->toEqual('1');

    expect($value3)->toBeString();
    expect($value3)->toEqual('1');
});

it('should cast to integer', function () {
    $value1 = Helpers::cast('1', 'integer');
    $value2 = Helpers::cast(1, 'integer');
    $value3 = Helpers::cast(true, 'integer');

    expect($value1)->toBeInt();
    expect($value1)->toEqual(1);

    expect($value2)->toBeInt();
    expect($value2)->toEqual(1);

    expect($value3)->toBeInt();
    expect($value3)->toEqual(1);
});

it('should cast to float', function () {
    $value1 = Helpers::cast('1', 'float');
    $value2 = Helpers::cast(1, 'float');
    $value3 = Helpers::cast(true, 'float');

    expect($value1)->toBeFloat();
    expect($value1)->toEqual(1);

    expect($value2)->toBeFloat();
    expect($value2)->toEqual(1);

    expect($value3)->toBeFloat();
    expect($value3)->toEqual(1);
});

it('should cast to enum', function () {
    $value1 = Helpers::cast('one', TestEnum::class);
    $value2 = Helpers::cast(TestEnum::ONE, TestEnum::class);

    expect($value1)->toBeInstanceOf(TestEnum::class);
    expect($value2)->toBeInstanceOf(TestEnum::class);
});

it('should throw error attempting to cast to non enum class', function () {
    $this->expectException(\Exception::class);
    Helpers::cast('one', Helpers::class);
});
