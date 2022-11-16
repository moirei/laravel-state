<?php

use Illuminate\Database\Eloquent\Model;
use MOIREI\State\Tests\ModelWithSimpleClassStatus;
use MOIREI\State\Tests\ModelWithUseAttributeClassStatus;
use MOIREI\State\Tests\TestState;

uses()->group('model-with-class-status-transitions');

beforeAll(function () {
    Model::unguard();
});

it('should update class state with transit function', function () {
    $model = new ModelWithSimpleClassStatus([
        'status' => TestState::ONE,
    ]);

    expect($model->status->is(TestState::ONE))->toBeTrue();

    $model->status->transitionTo('two');

    expect($model->status->is(TestState::TWO))->toBeTrue();
    expect($model->status->value())->toEqual('two');
});

it('should directly set class state', function () {
    $model = new ModelWithSimpleClassStatus([
        'status' => TestState::ONE,
    ]);

    $model->status->is(TestState::ONE);

    expect($model->status->is(TestState::ONE))->toBeTrue();

    $model->status = 'two';

    expect($model->status->is(TestState::TWO))->toBeTrue();
    expect($model->status->value())->toEqual('two');
});

it('should update state with transit function', function () {
    $model = new ModelWithUseAttributeClassStatus([
        'status' => TestState::ONE,
    ]);

    expect($model->status->is(TestState::ONE))->toBeTrue();

    $model->status->transitionTo('two');

    expect($model->status->is(TestState::TWO))->toBeTrue();
    expect($model->status->value())->toEqual('two');
});

it('should directly set state', function () {
    $model = new ModelWithUseAttributeClassStatus([
        'status' => TestState::THREE,
    ]);

    $model->status->is(TestState::THREE);

    expect($model->status->is(TestState::THREE))->toBeTrue();

    $model->status = 'four';

    expect($model->status->is(TestState::FOUR))->toBeTrue();
    expect($model->status->value)->toEqual('four');
    expect($model->status->value())->toEqual('four');
});
