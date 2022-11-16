<?php

use Illuminate\Database\Eloquent\Model;
use MOIREI\State\Tests\ModelWithSimpleEnumStatus;
use MOIREI\State\Tests\ModelWithUseAttributeEnumStatus;
use MOIREI\State\Tests\TestEnum;

uses()->group('model-with-enum-status-transitions');

beforeAll(function () {
    Model::unguard();
});

it('should update enum state with transit function', function () {
    $model = new ModelWithSimpleEnumStatus([
        'status' => TestEnum::ONE->value,
    ]);

    expect($model->status->is(TestEnum::ONE))->toBeTrue();

    $model->status->transitionTo('two');

    expect($model->status->is(TestEnum::TWO))->toBeTrue();
    expect($model->status->value())->toEqual('two');
});

it('should directly set enum state', function () {
    $model = new ModelWithSimpleEnumStatus([
        'status' => TestEnum::ONE->value,
    ]);

    $model->status->is(TestEnum::ONE);

    expect($model->status->is(TestEnum::ONE))->toBeTrue();

    $model->status = 'two';

    expect($model->status->is(TestEnum::TWO))->toBeTrue();
    expect($model->status->value())->toEqual('two');
    expect($model->status->value)->toEqual('two');
});

it('should update state with transit function', function () {
    $model = new ModelWithUseAttributeEnumStatus([
        'status' => TestEnum::ONE->value,
    ]);

    expect($model->status->is(TestEnum::ONE))->toBeTrue();

    $model->status->transitionTo('two');

    expect($model->status->is(TestEnum::TWO))->toBeTrue();
    expect($model->status->value())->toEqual('two');
});

it('should directly set state', function () {
    $model = new ModelWithUseAttributeEnumStatus([
        'status' => TestEnum::THREE->value,
    ]);

    $model->status->is(TestEnum::THREE);

    expect($model->status->is(TestEnum::THREE))->toBeTrue();

    $model->status = 'four';

    expect($model->status->is(TestEnum::FOUR))->toBeTrue();
    expect($model->status->value())->toEqual('four');
    expect($model->status->value)->toEqual('four');
});
