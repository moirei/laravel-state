<?php

namespace MOIREI\State\Tests\Database;

use MOIREI\State\State;

class ClassPostStatus extends State
{
    const ONE = 'one';

    const TWO = 'two';

    const THREE = 'three';

    const FOUR = 'four';

    public static function states()
    {
        return [
            State::on(self::ONE, self::TWO),
            State::on(self::TWO, [self::ONE, self::THREE]),
            State::on(self::THREE, [self::TWO, self::FOUR]),
        ];
    }
}
