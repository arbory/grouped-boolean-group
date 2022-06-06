<template>
  <DefaultField :field="field"
                :errors="errors"
                :show-help-text="showHelpText"
                :full-width-content="field.fullWidthContent"
                :class="field.fieldClasses"
  >
    <template #field>
        <div class="flex justify-end field-collapse"
             :class="{'cursor-pointer': field.globalToggle}"
             @click="field.globalToggle && collapseAll()"
        >
            <button v-if="field.globalToggle"
                    type="button"
                    class="btn outline-none focus:outline-none"
            >
                <IconCollapsible class="fill-none stroke-80"
                                  :value="field[openToggleKey]"
                                  height="18"
                                  width="18"
                />
            </button>
        </div>
        <div class="field-content" v-if="!field.globalToggle || field[openToggleKey]">
            <div class="flex justify-between mb-6 mt-2">
                <div class="flex flex-col w-1/2" v-if="field.hasFilters && field.filterOptions.length > 0">
                    <label for="permission-filter" class="text-80 leading-tight mb-2">
                        {{ field.filterLabel }}
                    </label>
                    <SelectControl class="w-full"
                                    :class="{ 'border-danger': hasError }"
                                    data-testid="permission-filter-select"
                                    dusk="permission-filter-select"
                                    name="permission-filter-select"
                                    id="permission-filter"
                                    :options="field.filterOptions"
                                    :value="selectedGroup"
                                    :selected="selectedGroup"
                                    @change="filterGroups($event)"
                    >
                        <option value="" selected>
                            __
                        </option>
                    </SelectControl>
                </div>
                <div class="flex justify-end">
                    <CheckboxWithLabel
                        v-if="field.globalSelectAll"
                        class="mt-2"
                        :class="{'semi-active': semiActive}"
                        @input="selectAll($event)"
                        :checked="active"
                    >
                        <h4>{{ field.globalSelectAllLabel }}</h4>
                    </CheckboxWithLabel>
                </div>
            </div>
            <div :class="{'flex flex-row flex-wrap full-width justify-between': field.fullWidthColumnLayout, 'row-layout': field.rowLayout }">
                <div v-for="(optionGroup, groupName) in value">
                    <div v-if="!selectedGroup || selectedGroup === groupName"
                         class="flex flex-col items-start boolean-group"
                         :class="{'w-1/3': field.fullWidthColumnLayout, 'w-full': field.fullWidthContent, 'border-b border-40 py-3': !field.fullWidthColumnLayout}"
                    >
                        <div class="w-full flex justify-between items-center"
                             :class="{'cursor-pointer': field.groupToggle, 'border-b border-40 py-3': field.fullWidthColumnLayout}"
                             @click.self="field.groupToggle && collapseGroup(groupName)"
                        >
                            <CheckboxWithLabel
                                v-if="field.groupSelectAll"
                                class="mt-2"
                                :class="{'semi-active': groups[groupName].semiActive}"
                                :key="groupName"
                                :name="groupName"
                                :checked="groupActive(groupName)"
                                @input.self="selectGroup($event, groupName)"
                            >
                                <h4>{{ groupName }}</h4>
                            </CheckboxWithLabel>
                            <h4 class="mb-4" v-else>{{ groupName }}</h4>
                            <button v-if="field.groupToggle"
                                    type="button"
                                    class="btn outline-none focus:outline-none flex pointer-events-none"
                            >
                                <IconCollapsible class="fill-none stroke-80"
                                                 :value="optionGroup[openToggleKey]"
                                                 height="18"
                                                 width="18"
                                />
                            </button>
                        </div>
                        <div class="w-full mt-6 mb-3"
                             :class="{'flex flex-row w-full justify-between': field.rowLayout, 'border-b border-40 pb-5': field.fullWidthColumnLayout}"
                             v-if="!field.groupToggle || optionGroup[openToggleKey]"
                        >
                            <CheckboxWithLabel
                                class="mt-2 checkbox-control"
                                v-for="option in optionGroup.values"
                                :key="option.name"
                                :name="option.name"
                                :checked="option.checked"
                                @input="toggle($event, option, groupName)"
                                :disabled="isReadonly"
                            >
                                {{ option.label }}
                            </CheckboxWithLabel>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
  </DefaultField>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova';
import HandlesGroupStates from '../mixins/HandlesGroupStates';
import Collapsible from '../mixins/Collapsible';
import SelectsMultiple from '../mixins/SelectsMultiple';
import IconCollapsible from "./Icons/IconCollapsible";

export default {
    components: {IconCollapsible},
    mixins: [
        HandlesValidationErrors,
        FormField,
        HandlesGroupStates,
        Collapsible,
        SelectsMultiple
    ],

    data: () => ({
        value: [],
        openToggleKey: 'openInForm',
        selectedGroup: null
    }),

    methods: {
        /*
         * Set the initial value for the field
         */
        setInitialValue() {

            this.field.value = this.field.value || {};

            let options = Object.assign(this.field.options, {});

            Object.keys(options).forEach((key) => {
                options[key].values.forEach((item) => {
                    item.checked = this.field.value[key][item.name] || false;
                });
            });

            this.value = options;

            this.refreshGroupState(this.value);
        },

        /**
         * Provide a function that fills a passed FormData object with the
         * field's internal value attribute.
         */
        fill(formData) {
            formData.append(this.field.attribute, JSON.stringify(this.finalPayload))
        },

        /**
         * Toggle the option's value.
         */
        toggle(event, option, group) {
            const firstOption = _(this.value[group].values).find(o => o.name == option.name);
            firstOption.checked = event.target.checked;

            this.refreshGroupState(this.value);
        },

        filterGroups(value) {
            this.selectedGroup = value;
        }
    },

    computed: {
        /**
         * Return the final filtered json object
         */
        finalPayload() {
            return _(this.value)
                .map((options, groupName) => [groupName, options])
                .fromPairs()
                .value()
        },
    },
}
</script>
