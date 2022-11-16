<?php

use MOIREI\State\Tests\TestEnum;

uses()->group('has-enum-state');

it('should transition state and update enum value', function () {
    $enum = TestEnum::ONE;

    $enum = $enum->transitionTo('two');

    // expect($enum->value)->toEqual('two');
    expect($enum->value())->toEqual('two');
    expect($enum->is(TestEnum::TWO))->toBeTrue();
});
