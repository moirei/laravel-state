<?php

namespace MOIREI\State\Tests\Database;

use MOIREI\State\State;
use MOIREI\State\Traits\HasEnumState;

enum EnumPostStatus: string
{
    use HasEnumState;

    case CREATED = 'created';
    case PUSBLSHED = 'published';
    case PENDING_APPROVAL = 'pending';
    case APPROVED = 'approved';
    case ARCHIVED = 'archived';

    public static function states()
    {
        return [
            State::on(self::CREATED, self::PUSBLSHED),
            State::on(self::PUSBLSHED, self::PENDING_APPROVAL),
            State::on(self::PENDING_APPROVAL, self::APPROVED),
            State::on(self::APPROVED, [self::PENDING_APPROVAL]),
        ];
    }
}
