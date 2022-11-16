<?php

namespace MOIREI\State\Exceptions;

use Exception;
use MOIREI\State\Helpers;

class IllegalStateTransition extends Exception
{
    public function __construct(mixed $state, mixed $from, mixed $to)
    {
        $stateClass = is_string($state) ? $state : get_class($state);
        $from = Helpers::rawValue($from);
        $to = Helpers::rawValue($to);
        parent::__construct("Illegal state transition from [$from] to [$to] on [$stateClass]");
    }
}
