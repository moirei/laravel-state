<?php

use Illuminate\Database\Eloquent\Model;
use MOIREI\State\Tests\ModelWIthDynamicState;
use MOIREI\State\Tests\TestState;

uses()->group('dynamic-states');
beforeAll(function () {
    Model::unguard();
});

it('should update enum state with transit function', function () {
    $model = new ModelWIthDynamicState([
        'status' => TestState::ONE,
    ]);

    expect($model->status->is(TestState::ONE))->toBeTrue();

    $model->status->transitionTo('two');

    expect($model->status->is(TestState::TWO))->toBeTrue();
    expect($model->status->value())->toEqual('two');
});
