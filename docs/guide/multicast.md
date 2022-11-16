# Multicast Attributes

Sometimes model states are represented in the database by multiple timestamp columns. The belog example of `PostStatus` state is derived from multiple attributes.

Multicasting states to attributes can even be used in conjunction with direct casting.

```php
enum PostStatus: string
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
                get: fn ($state, Post $model, $attributes) => (bool) $model->published_at && ! $model->approved_at,
                set: fn () => ['published_at' => null],
            ),
            State::on(
                self::APPROVED,
                [self::PENDING_APPROVAL],
                get: fn ($state, Post $model, $attributes) => (bool) $model->approved_at,
                set: fn () => ['approved_at' => now()],
            ),
            State::on(
                self::ARCHIVED,
                get: function($state, Post $model, $attributes){
                  if($model->published_at){
                    // archived after 100 years
                    return $model->published_at->after(now()->addYears(100));
                  }
                  return false;
                },
            ),
        ];
    }
}
```

Use state as usual:

```php
/**
 * @property PostStatus $status
 */
class Post extends Model{
    protected $casts = [
        'created_at' => 'datetime',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    protected function status(): Attribute{
        return PostStatus::useAttribute();
    }
}
```

Now, updating state will update model attributes:

```php
if($post->status->is('pending')){
    $post->status->markAs('approved');
    dump($post->approved_at); // now
}
```
