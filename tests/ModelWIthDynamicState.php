<?php

namespace MOIREI\State\Tests;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use MOIREI\State\State;

/**
 * @property State $status
 */
class ModelWIthDynamicState extends Model
{
    protected function status(): Attribute
    {
        return State::make([
            State::on(TestState::ONE, TestState::TWO),
            State::on(TestState::TWO, [TestState::ONE, TestState::THREE]),
            State::on(TestState::THREE, [TestState::TWO, TestState::FOUR]),
        ]);
    }
}
