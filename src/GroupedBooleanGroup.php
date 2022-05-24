<?php

namespace ArboryNova\GroupedBooleanGroup;

use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Http\Requests\NovaRequest;

class GroupedBooleanGroup extends BooleanGroup
{
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
     * The field's component.
     *
     * @var string
     */
    public $component = 'grouped-boolean-group';

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
    public function options($options): GroupedBooleanGroup
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

    public function setOptionLabelAttribute($optionLabelAttribute): GroupedBooleanGroup
    {
        $this->optionLabelAttribute = $optionLabelAttribute;

        return $this;
    }

    public function setFilterLabel(string $filterLabel): GroupedBooleanGroup
    {
        $this->filterLabel = $filterLabel;

        return $this;
    }

    public function setGlobalSelectAllLabel(string $globalSelectAllLabel): GroupedBooleanGroup
    {
        $this->globalSelectAllLabel = $globalSelectAllLabel;

        return $this;
    }

    public function setRowLayout(): GroupedBooleanGroup
    {
        $this->withMeta(['rowLayout' => true, 'stacked' => true, 'fullWidthContent' => true]);

        return $this;
    }

    public function setFullWidthColumnLayout(): GroupedBooleanGroup
    {
        $this->withMeta(['fullWidthColumnLayout' => true, 'stacked' => true, 'fullWidthContent' => true]);

        return $this;
    }

    public function setFullWidth(): GroupedBooleanGroup
    {
        $this->withMeta(['fullWidthContent' => true, 'stacked' => true]);

        return $this;
    }

    public function withGlobalToggling(): GroupedBooleanGroup
    {
        $this->withMeta(['fieldClasses' => 'relative', 'globalToggle' => true]);

        return $this;
    }

    public function withGroupToggling(): GroupedBooleanGroup
    {
        $this->withMeta(['groupToggle' => true]);

        return $this;
    }

    public function openGroupsInDetails(): GroupedBooleanGroup
    {
        $this->openGroupsInDetails = true;

        return $this;
    }

    public function openGroupsInForm(): GroupedBooleanGroup
    {
        $this->openGroupsInForm = true;

        return $this;
    }

    public function openGroups(): GroupedBooleanGroup
    {
        $this->openGroupsInDetails();
        $this->openGroupsInForm();

        return $this;
    }

    public function openField(): GroupedBooleanGroup
    {
        $this->openFieldInDetails();
        $this->openFieldInForm();

        return $this;
    }

    public function openFieldInDetails(): GroupedBooleanGroup
    {
        $this->openFieldInDetails = true;

        return $this;
    }

    public function openFieldInForm(): GroupedBooleanGroup
    {
        $this->openFieldInForm = true;

        return $this;
    }

    public function withGlobalSelectAll(): GroupedBooleanGroup
    {
        $this->withMeta(['globalSelectAll' => true, 'fieldClasses' => 'relative']);

        return $this;
    }

    public function withGroupSelectAll(): GroupedBooleanGroup
    {
        $this->withMeta(['groupSelectAll' => true]);

        return $this;
    }

    /**
     * @param array $filterOptions
     * @return $this
     */
    public function withFilters(array $filterOptions): GroupedBooleanGroup
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
            'globalSelectAllLabel' => $this->globalSelectAllLabel ?? __('grouped-boolean-group::field.global_select_all'),
            'filterLabel' => $this->filterLabel ?? __('grouped-boolean-group::field.filter_label')
        ], parent::jsonSerialize());
    }
}
