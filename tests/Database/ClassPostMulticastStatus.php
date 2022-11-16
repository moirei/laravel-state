<?php

namespace MOIREI\State\Tests\Database;

use MOIREI\State\State;

class ClassPostMulticastStatus extends State
{
    const CREATED = 'created';
    const PUSBLSHED = 'published';
    const PENDING_APPROVAL = 'pending';
    const APPROVED = 'approved';
    const ARCHIVED = 'archived';

    public static function states()
    {
        return [
            State::on(
                self::CREATED,
                self::PUSBLSHED,
                get: fn ($state, Post $model, $attributes) => (bool) $model->created_at && !$model->published_at
            ),
            State::on(
                self::PUSBLSHED,
                self::PENDING_APPROVAL,
                get: fn ($state, Post $model, $attributes) => (bool) $model->published_at,
                set: fn () => ['published_at' => now()],
            ),
            State::on(
                self::PENDING_APPROVAL,
                self::APPROVED,
                get: fn ($state, Post $model, $attributes) => (bool) $model->published_at && !$model->approved_at,
                set: fn () => ['published_at' => null],
            ),
            State::on(
                self::APPROVED,
                [self::PENDING_APPROVAL],
                get: fn ($state, Post $model, $attributes) => (bool) $model->approved_at,
                set: fn () => ['approved_at' => now()],
            ),
        ];
    }
}
