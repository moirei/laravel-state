# Introduction

This package is intented to simplify model states. States can be defined dynamically or using an predefined `State` class or Enum.

When using predefined states, they can be used in a model in two ways:

1. As attribute casts
2. As Laravel 9+ Attributes

```php
class MyModel extends Model{
  protected $casts = [
    'status' => MyModelStatus::class
  ];

  // or

  protected function status(): Attribute{
    return MyModelStatus::useAttribute();
  }
}
```

## Conventions

A few important conventions to note:

### Multicast Attributes

By default, both of the definitions in the example above will map the state to a column named `status` in the database. Use the `get`, and `set` options when to defining state to cast to/from multiple attributes.

### Transitions

Methods `markAs` and `transitionTo` are used for transitions.
Setting states directly will also triger transition checks.

### Types

This isn't required when using enums.
With class based states, you can specify types to cast and type check against. The default type is `string`.

```php
class OrderStatus extends State{
  public static function type(): string{
    return \App\Enums\OrderStatus::class;
  }

  public static function states(): array{
    return [
      ...
    ];
  }
}
```

The below will return a `App\Enums\OrderStatus` type.

```php
$order->status->value();
```

### Defaults

By the default, the initial state is taken from the first state entity.
Provide your own default if you dont want this behaviour or if the initial is possibly null.

```php
class OrderStatus extends State{

  public static function default(){
    return self::PENDING;
  }

  public static function states(): array{
    return [
      State::on(self::DEFAULT, ...),
    ];
  }
}
```

### Accessing state values

Both class and (of course) enum state provide a `value` property and method.

```php
$model->state->value;
// or
$model->state->value();
```

Using the method returns a computed up-to-date value of the state.

### Checking states

You can check any state with the `is` method:

```php
if($model->state->is('pending')){
  ...
}
```

### Get next states

You can get next possible states using the `next` method:

```php
$nextStates = $model->state->next();
```

### Check transitions

Methods `canTransitionTo` and `canBe` can be used to check if a state can be transitioned to.

```php
if($model->state->canTransitionTo('pending')){
  ...
}
```
