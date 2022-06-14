<template>
  <PanelItem :index="index" :field="field">
      <template #value>
          <div class="flex justify-end details-field-collapse"
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
          <div v-if="!field.globalToggle || field[openToggleKey]"
               class="mt-8"
               :class="{'flex flex-wrap flex-col row-layout': field.rowLayout, 'flex flex-wrap flex-row column-layout': field.fullWidthColumnLayout, 'flex flex-wrap full-width': field.fullWidthContent}"
          >
              <div v-if="Object.keys(value).length !== 0"
                   v-for="(optionGroup, groupName) in value"
                   class="border-b border-40 details-boolean-group mb-6 mr-4"
                   :class="{'w-full': field.fullWidthContent}"
              >
                  <div class="mb-4 flex items-center justify-between"
                       :class="{'cursor-pointer': field.groupToggle}"
                       @click="field.groupToggle && collapseGroup(groupName)"
                  >
                      <div class="flex items-center">
                            <span :class="{'bg-warning-light': groups[groupName].semiActive,
                                'bg-success-light': groups[groupName].active,
                                'bg-danger-light': !groups[groupName].active && !groups[groupName].semiActive}"
                                  class="bg-warning-light inline-flex items-center py-1 px-2 rounded-full font-bold text-sm leading-tight mr-2">
                            <Icon v-if="groups[groupName].active"
                                  type="check-circle"
                                  width="24"
                                  height="24"
                                  viewBox="0 0 24 24"
                                  class="text-green-500"/>
                            <Icon v-else-if="groups[groupName].semiActive"
                                  type="minus-circle"
                                  width="24"
                                  height="24"
                                  viewBox="0 0 24 24"
                                  class="fill-none text-yellow-500"/>
                            <Icon v-else-if="!groups[groupName].semiActive && !groups[groupName].active"
                                  type="x-circle"
                                  width="24"
                                  height="24"
                                  viewBox="0 0 24 24"
                                  class="text-red-500"/>
                          </span>
                          <h4>{{ groupName }}</h4>
                      </div>
                      <button v-if="field.groupToggle"
                              type="button"
                              class="btn outline-none focus:outline-none flex"
                      >
                          <IconCollapsible class="fill-none stroke-80"
                                            :value="optionGroup[openToggleKey]"
                                            height="18"
                                            width="18"
                          />
                      </button>
                  </div>
                  <ul class="list-reset mb-3 mt-6"
                      :class="{'flex flex-row justify-between': field.rowLayout}"
                      v-if="!field.groupToggle || optionGroup[openToggleKey]"
                  >
                      <li v-for="option in optionGroup.values" class="mb-1" :class="{'mr-4': field.rowLayout}">
                          <span
                              :class="classes[option.checked]"
                              class="inline-flex items-center py-1 pl-2 pr-3 rounded-full font-bold text-sm leading-tight"
                          >
                            <IconBoolean :value="option.checked" width="20" height="20"/>
                            <span class="ml-1">{{ option.label }}</span>
                          </span>
                      </li>
                  </ul>
              </div>
              <span v-else>{{ this.field.noValueText }}</span>
          </div>
      </template>
  </PanelItem>
</template>

<script>
import HandlesGroupStates from '../mixins/HandlesGroupStates';
import Collapsible from '../mixins/Collapsible';
import IconCollapsible from "./Icons/IconCollapsible";

export default {
    components: {IconCollapsible},
    mixins: [HandlesGroupStates, Collapsible],

    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data: () => ({
        value: [],
        classes: {
            true: 'bg-success-light text-success-dark',
            false: 'bg-danger-light text-danger-dark',
        },
        openToggleKey: 'openInDetails',
    }),

    created() {
        this.field.value = this.field.value || {}

        let options = Object.assign(this.field.options, {});

        Object.keys(options).forEach((key) => {
            options[key].values.forEach((item) => {
                item.checked = this.field.value[key][item.name] || false;
            });
        });

        this.value = options;

        this.refreshGroupState(this.value);
    },

    methods: {
        collapseAll() {
            let field = Object.assign({}, this.field);

            field[this.openToggleKey] = !field[this.openToggleKey];

            this.field = field;
        },
    }
}
</script>
