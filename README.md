# Laravel State

Like many things in Laravel, managing model states should be a breeze.

There has been a few solutions before the advent of PHP Enums, hwoever, this package is an attempt at what managing strict state in Laravel should feel like.

## Documentation

All documentation is available at [the documentation site](https://moirei.github.io/state).

## Features

- Strictly defined states and trasitions
- PHP Enums support 💪
- Cast state to and from multiple attributes

## Basic Example

```php
use MOIREI\State\State;

/**
 * @property State $status
 */
class Order extends Model{
    protected function status(): Attribute{
        return State::make([
            State::on('created', 'paid'),
            State::on('paid', ['created', 'completed'])
            State::on('completed', 'archived')
        ]);
    }
}
```

Now transition states

```php
dump($order->status->value); // `created`

$order->status->transitionTo('paid');

dump($order->status->value); // `paid`
```

### With PHP Enum

```php
use MOIREI\State\Traits\CastsEnumAttributesState;
use MOIREI\State\Traits\HasEnumState;
use MOIREI\State\State;

...

enum OrderStatus: string{
    use HasEnumState;

    case PENDING = 'pending';
    case PAID = 'paid';
    case CLOSED = 'closed';

    public static function states(){
        return [
            State::on(self::PENDING, [self::PAID, self::CLOSED]),
            State::on(self::PAID, self::CLOSED),
        ];
    }
}

...

/**
 * @property OrderStatus $status
 */
class Order extends Model{
    use CastsEnumAttributesState; // only if using casts

    protected $casts = [
        'status' => OrderStatus::class
    ];

    // or

    protected function status(): Attribute{
        return OrderStatus::useAttribute();
    }
}

...

// accepts enum value but fails if invalid
if($order->status->is('pending')){
    $order->status->transitionTo(OrderStatus::PAID);
}

// should throw
if($order->status->is('closed')){
    $order->status->transitionTo(...);
}
```

### With state object

```php
use MOIREI\State\State;

...

final class OrderStatus extends State{
    const PENDING = 'pending';
    const PAID = 'paid';
    const CLOSED = 'closed';

    public static function states(){
        return [
            State::on(static::PENDING, [static::PAID, static::CLOSED]),
            State::on(static::PAID, static::CLOSED),
        ];
    }
}

...

/**
 * @property OrderStatus $status
 */
class Order extends Model{

    protected $casts = [
        'status' => OrderStatus::class
    ];

    // or

    protected function status(): Attribute{
        return OrderStatus::useAttribute();
    }
}

...

// same as with enum above
if($order->status->is('pending')){
    $order->status->transitionTo('paid');
}
```

## Installation

```bash
composer require moirei/state
```

## Tests

```bash
composer test
```
