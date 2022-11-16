<?php

namespace MOIREI\State\Tests\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MOIREI\State\Tests\EnumPostStatus;

/**
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $published_at
 * @property \Carbon\Carbon $approved_at
 * @property \Carbon\Carbon $archived_at
 * @property EnumPostStatus $status
 */
class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $guard = [];

    protected $casts = [
        'created_at' => 'datetime',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
        'archived_at' => 'datetime',
    ];
}
