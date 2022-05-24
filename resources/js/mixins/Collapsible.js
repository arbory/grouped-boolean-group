export default {
    methods: {
        collapseAll() {
            this.field[this.openToggleKey] = !this.field[this.openToggleKey];
        },

        collapseGroup(groupName) {
            this.value[groupName][this.openToggleKey] = !this.value[groupName][this.openToggleKey];
        },
    }
}
