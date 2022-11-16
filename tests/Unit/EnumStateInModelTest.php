<?php

use Illuminate\Database\Eloquent\Model;
use MOIREI\State\Tests\ModelWithSimpleEnumStatus;
use MOIREI\State\Tests\ModelWithUseAttributeEnumStatus;
use MOIREI\State\Tests\TestEnum;

uses()->group('enum-state-in-model');

beforeAll(function () {
    Model::unguard();
});

it('should cast enum attribute from casts model property', function () {
    $model = new ModelWithSimpleEnumStatus([
        'status' => TestEnum::ONE,
    ]);

    expect($model->status)->toBeInstanceOf(TestEnum::class);
    expect($model->status->is(TestEnum::ONE))->toBeTrue();
});

it('should cast enum attribute from casts model property using enum value', function () {
    $model = new ModelWithSimpleEnumStatus([
        'status' => TestEnum::ONE->value,
    ]);

    expect($model->status)->toBeInstanceOf(TestEnum::class);
    expect($model->status->is(TestEnum::ONE))->toBeTrue();
});

it('should cast enum attribute from attributes function', function () {
    $model = new ModelWithUseAttributeEnumStatus([
        'status' => TestEnum::ONE->value,
    ]);

    expect($model->status)->toBeInstanceOf(TestEnum::class);
    expect($model->status->is(TestEnum::ONE))->toBeTrue();
});
