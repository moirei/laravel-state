<?php

namespace MOIREI\State\Tests;

use MOIREI\State\State;
use MOIREI\State\Traits\HasEnumState;

enum TestEnum: string
{
    use HasEnumState;

    case ONE = 'one';
    case TWO = 'two';
    case THREE = 'three';
    case FOUR = 'four';

    public static function states()
    {
        return [
            State::on(self::ONE, self::TWO),
            State::on(self::TWO, [self::ONE, self::THREE]),
            State::on(self::THREE, [self::TWO, self::FOUR]),
        ];
    }
}
