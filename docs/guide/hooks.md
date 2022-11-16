# Hooks

Hooks can be used to perform important operations before and after state transitions.

## Before hook

In the example below, we can execute one or multiple hooks before transitioning states.

```php
 public static function states(): array{
    return [
      State::on(
        ...
        before: function($fromState, $toState){
          //
        },
      ),
      State::on(
        ...
        before: [
          function(){
            //
          }
        ],
      ),
    ];
}
```

If any of the hooks return `false`, the transition is exited.

## After hook

Likewise for after a state has been transitioned.

```php
 public static function states(): array{
    return [
      State::on(
        ...
        after: function(){
          //
        },
      ),
      State::on(
        ...
        after: [
          function(){
            //
          }
        ],
      ),
    ];
}
```
