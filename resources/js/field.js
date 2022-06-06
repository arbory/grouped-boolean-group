Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-grouped-boolean-field-group', require('./components/IndexField'))
  Vue.component('detail-nova-grouped-boolean-field-group', require('./components/DetailField'))
  Vue.component('form-nova-grouped-boolean-field-group', require('./components/FormField'))
  Vue.component('icon-chevron-down', require('./components/Icons/ChevronDown'))
  Vue.component('icon-chevron-up', require('./components/Icons/ChevronUp'))
  Vue.component('icon-minus-circle', require('./components/Icons/MinusCircle'))
  Vue.component('icon-collapsible', require('./components/Icons/Collapsible'))
})
