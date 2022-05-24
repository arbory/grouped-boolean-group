## Grouped boolean group field

Field which extends default Laravel Nova BooleanGroup field to be able to pass grouped collections.

To resolve and hydrate the field values, custom callbacks can be passed either to field constructor as arguments
or via ```fillUsing()``` and ```resolveUsing()``` field callbacks. 

For field values, either pass a grouped collection or a custom callback to ```options()``` field callback.

#### Basic Usage:

Field instantiation with all arguments passed via constructor:
```php
GroupedBooleanGroup::make(
    __('nova-permission-tool::roles.permissions'),
    'permissions',
    'name',
    function ($value, $model) {
        return $model->resolveNovaPermissions();
    },
    function ($request, $model, string $attribute, string $requestAttribute) {
        return $model->saveNovaPermissions(json_decode($request[$requestAttribute], true));
    }
)->options(Permission::getPermissionsGroups())
```

or via field callbacks:

```php
GroupedBooleanGroup::make(
    __('nova-permission-tool::roles.permissions'),
    'permissions',
    'name'
)
->fillUsing(function ($request, $model, string $attribute, string $requestAttribute) {
    return $model->saveNovaPermissions(json_decode($request[$requestAttribute], true));
})
->resolveUsing(function ($value, $model) {
    return $model->resolveNovaPermissions();
})
->options(Permission::getPermissionsGroups())
```
