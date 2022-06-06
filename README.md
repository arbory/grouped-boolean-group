# Grouped boolean group field

Field which extends default Laravel Nova BooleanGroup field to be able to pass grouped collections.

To resolve and hydrate the field values, custom callbacks can be passed either to field constructor as arguments
or via ```fillUsing()``` and ```resolveUsing()``` field callbacks. 

For field values, either pass a grouped collection or a custom callback to ```options()``` field callback.

## Installation

```composer require arbory/nova-grouped-boolean-field-group```

## Basic Usage:

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

### Mapping field options

By default, field option key and value pairs will contain value of specified ```optionNameAttribute```. 
For custom option labels, use ```setOptionLabelAttribute()``` field method. 

### Customising field layout

The field has 3 custom layouts available - row layout, full width column layout and full width layout.

Row layout can be enabled via ```setRowLayout()``` method.

Full width column layout can be enabled via ```setFullWidthColumnLayout()``` method.

Full width layout can be enabled via ```setFullWidth()``` method.

### Field group and global toggles

Field groups and entire field content can be made collapsible. 

Field group toggles can be enabled via ```withGroupToggling()``` method. Global field toggle can be enabled via ```withGlobalToggle()``` method.

When using toggles, it is also possible to specify toggle state, whether the field or groups should be open on page load.

To keep groups open in form view, use ```openGroupsInForm()``` method.
To keep groups open in details view, use ```openGroupsInDetail()``` method.
To keep groups open in both views, use ```openGroups()``` method.

To keep field open in form view, use ```openFieldInForm()``` method. 
To keep field open in details view, use ```openFieldInDetails()``` method. 
To keep field open in both views, use ```openField()``` method. 

### Group and global select all

This feature enables a checkbox, which will make it possible to toggle an entire group or all checkboxes within this field.

To enable group select all checkbox, use ```withGroupSelectAll()```.
To enable global select all checkbox, use ```withGlobalSelectAll()```.

### Filters

It is also possible to add filters via ```withFilters()``` method.

The method accepts a single parameter with filter options of type array. 
This filter toggles field groups by group names that come from field field options collection. 
Filter options should be keyed as ```['label' => $label, 'value' => $value]```. 

A brief example of preparing a filter options collection:

```php
return Permission::query()
        ->pluck('owner_type')
        ->unique()
        ->map(function ($permissionOwner) {
            $filterOption = self::formatPermissionGroupKey($permissionOwner);

            return ['label' => $filterOption, 'value' => $filterOption];
        })
        ->values()
        ->toArray();
```

### Custom translations for global toggle and filter field

It is possible to set custom field labels for global toggle and filter field.

To do so, use ```setGlobalSelectAllLabel()``` or ```setFilterLabel()``` method and pass a string of your choice.