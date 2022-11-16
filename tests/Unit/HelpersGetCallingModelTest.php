<?php

use Illuminate\Database\Eloquent\Model;
use MOIREI\State\Helpers;

uses()->group('helpers', 'helpers-get-calling-model');

it('should get the calling model and attribute', function () {
    $model = new class extends Model
    {
        public function testAttribute()
        {
            return Helpers::getCallingModel();
        }
    };

    [$m, $key] = $model->testAttribute();

    expect($m)->toBeInstanceOf(Model::class);
    expect($key)->toEqual('testAttribute');
});

it('should get the calling model and attribute [nested]', function () {
    $model = new class extends Model
    {
        public function testAttribute()
        {
            $proxyClass = new class
            {
                public function get()
                {
                    return Helpers::getCallingModel();
                }
            };

            return $proxyClass->get();
        }
    };

    [$m, $key] = $model->testAttribute();

    expect($m)->toBeInstanceOf(Model::class);
    expect($key)->toEqual('testAttribute');
});
