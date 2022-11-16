# State Transitions

To transition state, use the `transitionTo` or `markAs` method. These methods accept primitive and enum types.

```php
 public static function states(): array{
    return [
      State::on(self::PAID, self::PENDING),
      State::on(self::PENDING, [
        self::PAID,
        self::BLOCKED,
      ]),
      State::on(self::PENDING,
        next: [
          self::PAID,
          self::BLOCKED,
        ],
        before: function(){
          //
        },
        after: function(){
          //
        }
    ),
}
```

Attempts to transition to states not defined as `next` states of the active state will throw an exception.

```php
$model->state->markAs('state-two');
```

Directly assigning state value will also trigger the transition checks

```php
$model->state = 'state-two';
```

## Initial state

A static `default` method can be defined to specify the initial state. If not set, then the attribute caster tries to resolve the initial state from the defined state entities. If this doesn't resolve, then it takes the first entry.

```php
public static function default(){
    return 'state-one';
}
```

## Final state (s)

If there is no definition for a state or not defined states includes the state in its next valid states, then the state is considered a final state.

States `state-two` and `state-three` below are final states and may not be exited.

```php
public static function states(){
  return [
    ...,
    State::on('state-one', ['state-two', 'state-three']),
    State::on('state-two',
      before: function(){
        ...
      }
    ),
  ];
}
```

## Transition to one state

```php
public static function states(){
  return [
    ...,
    State::on('state-one', 'state-two'),
  ];
}
```

## Transition to multiple states

Provide an array to indicate multiple states. Use `'*'` to indicate all.

```php
public static function states(){
  return [
    ...,
    State::on('state-one', ['state-two', 'state-three']),
    State::on('state-two', '*'),
  ];
}
```
