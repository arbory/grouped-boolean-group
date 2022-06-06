<?php

namespace Arbory\NovaGroupedBooleanFieldGroup;

use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Http\Requests\NovaRequest;

class GroupedBooleanFieldGroup extends BooleanGroup
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-grouped-boolean-field-group';

    protected string $optionNameAttribute;

    protected ?string $optionLabelAttribute;

    protected bool $openGroupsInDetails = false;

    protected bool $openGroupsInForm = false;

    protected bool $openFieldInDetails = false;

    protected bool $openFieldInForm = false;

    protected ?string $filterLabel;

    protected ?string $globalSelectAllLabel;

    /**
     * @param $name
     * @param $optionNameAttribute
     * @param null $attribute
     * @param callable|null $resolveCallback
     */
    public function __construct($name, $attribute, $optionNameAttribute, callable $resolveCallback = null, callable $fillCallback = null)
    {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback
        );

        $this->fillCallback = $fillCallback;
        $this->optionNameAttribute = $optionNameAttribute;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */
    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (isset($this->fillCallback) && $request->exists($requestAttribute)) {
            return call_user_func(
                $this->fillCallback, $request, $model, $attribute, $requestAttribute
            );
        }

        return $this->fillAttributeFromRequest(
            $request, $requestAttribute, $model, $attribute
        );
    }

    /**
     * Set the options for the field.
     *
     * @param  array|\Closure|\Illuminate\Support\Collection
     * @return $this
     */
    public function options($options): GroupedBooleanFieldGroup
    {
        if (is_callable($options)) {
            $this->options = $options();
        } else {
            $this->options = with(collect($options), function ($options) {
                return $options->mapWithKeys(function ($optionGroup, $groupName) use ($options) {
                    $mappedOptions = $optionGroup->pluck(
                        $this->optionLabelAttribute ?? $this->optionNameAttribute, $this->optionNameAttribute
                    )->map(function ($key, $value) {
                        return ['name' => $key, 'label' => $value];
                    })->values()->toArray();

                    return [
                        $groupName => [
                            'values' => $mappedOptions,
                        ]
                    ];
                })->all();
            });
        }

        return $this;
    }

    public function setOptionLabelAttribute($optionLabelAttribute): GroupedBooleanFieldGroup
    {
        $this->optionLabelAttribute = $optionLabelAttribute;

        return $this;
    }

    public function setFilterLabel(string $filterLabel): GroupedBooleanFieldGroup
    {
        $this->filterLabel = $filterLabel;

        return $this;
    }

    public function setGlobalSelectAllLabel(string $globalSelectAllLabel): GroupedBooleanFieldGroup
    {
        $this->globalSelectAllLabel = $globalSelectAllLabel;

        return $this;
    }

    public function setRowLayout(): GroupedBooleanFieldGroup
    {
        $this->withMeta(['rowLayout' => true, 'stacked' => true, 'fullWidthContent' => true]);

        return $this;
    }

    public function setFullWidthColumnLayout(): GroupedBooleanFieldGroup
    {
        $this->withMeta(['fullWidthColumnLayout' => true, 'stacked' => true, 'fullWidthContent' => true]);

        return $this;
    }

    public function setFullWidth(): GroupedBooleanFieldGroup
    {
        $this->withMeta(['fullWidthContent' => true, 'stacked' => true]);

        return $this;
    }

    public function withGlobalToggling(): GroupedBooleanFieldGroup
    {
        $this->withMeta(['fieldClasses' => 'relative', 'globalToggle' => true]);

        return $this;
    }

    public function withGroupToggling(): GroupedBooleanFieldGroup
    {
        $this->withMeta(['groupToggle' => true]);

        return $this;
    }

    public function openGroupsInDetails(): GroupedBooleanFieldGroup
    {
        $this->openGroupsInDetails = true;

        return $this;
    }

    public function openGroupsInForm(): GroupedBooleanFieldGroup
    {
        $this->openGroupsInForm = true;

        return $this;
    }

    public function openGroups(): GroupedBooleanFieldGroup
    {
        $this->openGroupsInDetails();
        $this->openGroupsInForm();

        return $this;
    }

    public function openField(): GroupedBooleanFieldGroup
    {
        $this->openFieldInDetails();
        $this->openFieldInForm();

        return $this;
    }

    public function openFieldInDetails(): GroupedBooleanFieldGroup
    {
        $this->openFieldInDetails = true;

        return $this;
    }

    public function openFieldInForm(): GroupedBooleanFieldGroup
    {
        $this->openFieldInForm = true;

        return $this;
    }

    public function withGlobalSelectAll(): GroupedBooleanFieldGroup
    {
        $this->withMeta(['globalSelectAll' => true, 'fieldClasses' => 'relative']);

        return $this;
    }

    public function withGroupSelectAll(): GroupedBooleanFieldGroup
    {
        $this->withMeta(['groupSelectAll' => true]);

        return $this;
    }

    /**
     * @param array $filterOptions
     * @return $this
     */
    public function withFilters(array $filterOptions): GroupedBooleanFieldGroup
    {
        $this->withMeta([
            'hasFilters' => true,
            'filterOptions' => $filterOptions
        ]);

        return $this;
    }

    public function finalizeOptions(): array
    {
        $this->options = collect($this->options)->mapWithKeys(function ($optionGroup, $groupName) {
            $optionGroup['openInDetails'] = $this->openGroupsInDetails;
            $optionGroup['openInForm'] = $this->openGroupsInForm;

            return [$groupName => $optionGroup];
        })->all();

        return $this->options;
    }

    public function jsonSerialize(): array
    {
        return array_merge([
            'options' => $this->finalizeOptions(),
            'openInDetails' => $this->openFieldInDetails,
            'openInForm' => $this->openFieldInForm,
            'globalSelectAllLabel' => $this->globalSelectAllLabel ?? __('nova-grouped-boolean-field-group::field.global_select_all'),
            'filterLabel' => $this->filterLabel ?? __('nova-grouped-boolean-field-group::field.filter_label')
        ], parent::jsonSerialize());
    }
}
