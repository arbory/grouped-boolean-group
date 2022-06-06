import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'
import IconChevronDown from './components/Icons/IconChevronDown'
import IconChevronUp from './components/Icons/IconChevronUp'
import IconMinusCircle from './components/Icons/IconMinusCircle'
import IconCollapsible from './components/Icons/IconCollapsible'

Nova.booting((app, store) => {
  app.component('index-nova-grouped-boolean-field-group', IndexField)
  app.component('detail-nova-grouped-boolean-field-group', DetailField)
  app.component('form-nova-grouped-boolean-field-group', FormField)
  app.component('icon-chevron-down', IconChevronDown)
  app.component('icon-chevron-up', IconChevronUp)
  app.component('icon-minus-circle', IconMinusCircle)
  app.component('icon-collapsible', IconCollapsible)
})
