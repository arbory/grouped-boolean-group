<template>
    <icon
        v-if="value"
        :viewBox="viewBox"
        :width="width"
        :height="height"
        :type="type"
    />
    <icon
        v-else
        :viewBox="viewBox"
        :width="width"
        :height="height"
        :type="typeElse"
    />
</template>
<script>
export default {
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        type: {
            type: String,
            default: 'chevron-up',
        },
        typeElse: {
            type: String,
            default: 'chevron-down'
        },
        viewBox: {
            type: String,
            default: '0 0 20 20',
        },
        width: {
            type: [Number, String],
            default: 20,
        },
        height: {
            type: [Number, String],
            default: 20,
        },
    },
}
</script>
