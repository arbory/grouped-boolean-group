Nova.booting((Vue, router, store) => {
  Vue.component('index-grouped-boolean-group', require('./components/IndexField'))
  Vue.component('detail-grouped-boolean-group', require('./components/DetailField'))
  Vue.component('form-grouped-boolean-group', require('./components/FormField'))
  Vue.component('icon-chevron-down', require('./components/Icons/ChevronDown'))
  Vue.component('icon-chevron-up', require('./components/Icons/ChevronUp'))
  Vue.component('icon-minus-circle', require('./components/Icons/MinusCircle'))
  Vue.component('icon-collapsible', require('./components/Icons/Collapsible'))
})
