# Defining States

States can be defined in 3 ways

1. Within a state class
2. Witihin an a state enum
3. Dynamically in a model attribute definition

Any which way, the state can be directlly accessed the same

```php
if($order->status->is(OrderStatus::OPEN)){
  $order->status->transitionTo(OrderStatus::PAID);
}
```

## Enum States

It's recommended to use Enums over the provided State class.

Here's how an enum can be transformed into a model state.
Your enum doesn't have to be a backed enum.

```php
use MOIREI\State\Traits\HasEnumState;
...

enum OrderStatus: string{
  use HasEnumState;

  case OPEN = 'open';
  case PAID = 'paid';
  case CLOSED = 'closed';

  protected function states(){
    return [
      State::on(self::OPEN, self::PAID),
      ...
    ]
  }
}
```

Whether applied as a caster or an attribute, the accessed attribute value will return an enum.

```php
if($order->status->is(OrderStatus::OPEN)){
  // This is an enum:
  $enum = $order->status;
}
```

### Use as a caster

When using as a cast, the implenting model must use the `CastsEnumAttributesState` trait.

> This is a temporal solution until PHP Enums can be properly manipulated via `ReflectEnum`.

```php
use MOIREI\State\Traits\CastsEnumAttributesState;
...

/**
 * @property OrderStatus $status
 */
class Order extends Model{
  use CastsEnumAttributesState;

  protected $casts = [
    'status' => OrderStatus::class
  ];
}
```

### Use in attribute definition

A more elegant solution (for Laravel 9+) is to use Attributes.

```php
/**
 * @property OrderStatus $status
 */
class Order extends Model{
  protected function status(): Attribute{
    return OrderStatus::useAttribute();
  }
}
```

## Class States

```php
class OrderStatus extends State{
  const OPEN = 'open';
  const PAID = 'paid';
  const CLOSED = 'closed';

  public static function states(): array{
    return [
      State::on(self::PENDING, [
        self::PAID,
        self::BLOCKED,
      ]),
    ];
  }
}
```

### Use as a caster

State class can be casted without ascribing `CastsEnumAttributesState` trait to the model.

```php
/**
 * @property OrderStatus $status
 */
class Order extends Model{
  protected $casts = [
    'status' => OrderStatus::class
  ];
}
```

```php
$statusObject = $order->status;
```

### Use in attribute definition

An similar to Enums, its directly usable as an Attribute.

```php
/**
 * @property OrderStatus $status
 */
class Order extends Model{
  protected function status(): Attribute{
    return OrderStatus::useAttribute();
  }
}
```

## Dynamic States

It's possible to define state on the fly without referencing an external definition.

```php
/**
 * @property \MOIREI\State\State $status
 */
class Order extends Model{
  protected function status(): Attribute{
    return State::make([
      ...
    ]);
  }
}
```

This behaves exactly as using Enums or State classes.
It is also possible to provide the state type and default value.

```php
/**
 * @property \MOIREI\State\State $status
 */
class Order extends Model{
  protected function status(): Attribute{
    return State::make([
      State::on('pending', 'ready'),
      State::on('ready', ['pending', 'closed'])
    ],
      type: MyEnum::class,
      default: 'pending'
    );
  }
}
```

With type `MyEnum`, the actual state value returns an enum.

```php
$order->status->value->value;
```
