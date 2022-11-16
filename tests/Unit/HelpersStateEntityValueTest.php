<?php

use Illuminate\Database\Eloquent\Model;
use MOIREI\State\Helpers;
use MOIREI\State\State;

uses()->group('helpers', 'helpers-state-entity-value');

it('should get state entity value from state value', function () {
    $stateEntity = State::on('open');

    $state = new class extends State
    {
        public function __construct()
        {
            $this->model = new class extends Model
            {
            };
            $this->attributes = [];
        }

        public static function states()
        {
            return [];
        }
    };

    $stateEntityValue = Helpers::getStateEntityValue($stateEntity, $state->model, $state->attributes);

    expect($stateEntityValue)->toBeString();
    expect($stateEntityValue)->toEqual('open');
});

it('should get state entity value from getter', function () {
    $stateEntity = State::on(
        'open',
        get: fn () => [
            'a' => 1,
            'b' => 2,
        ]
    );

    $state = new class extends State
    {
        public function __construct()
        {
            $this->model = new class extends Model
            {
            };
            $this->attributes = [];
        }

        public static function states()
        {
            return [];
        }
    };

    $stateEntityValue = Helpers::getStateEntityValue($stateEntity, $state->model, $state->attributes);

    expect($stateEntityValue)->toBeArray();
    expect($stateEntityValue)->toHaveKey('a');
    expect($stateEntityValue)->toHaveKey('b');
});

it('should get state entity setter value from state value', function () {
    $stateEntity = State::on('open');

    $state = new class extends State
    {
        public function __construct()
        {
            $this->model = new class extends Model
            {
            };
            $this->attributes = [];
        }

        public static function states()
        {
            return [];
        }
    };

    $stateEntityValue = Helpers::setStateEntityValue($stateEntity, $state->model, $state->attributes);

    expect($stateEntityValue)->toBeString();
    expect($stateEntityValue)->toEqual('open');
});

it('should get state entity setter value from setter', function () {
    $stateEntity = State::on(
        'open',
        set: fn () => [
            'a' => 1,
            'b' => 2,
        ]
    );

    $state = new class extends State
    {
        public function __construct()
        {
            $this->model = new class extends Model
            {
            };
            $this->attributes = [];
        }

        public static function states()
        {
            return [];
        }
    };

    $stateEntityValue = Helpers::setStateEntityValue($stateEntity, $state->model, $state->attributes);

    expect($stateEntityValue)->toBeArray();
    expect($stateEntityValue)->toHaveKey('a');
    expect($stateEntityValue)->toHaveKey('b');
});
