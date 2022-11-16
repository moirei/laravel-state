<?php

namespace MOIREI\State\Exceptions;

use Exception;

class InvalidStateSet extends Exception
{
    public function __construct(mixed $state)
    {
        $stateClass = is_string($state) ? $state : get_class($state);
        parent::__construct("[$stateClass] is not a valid state set.");
    }
}
