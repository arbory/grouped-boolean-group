export default {
    data: () => ({
        groups: {},
        totalActiveGroups: [],
        totalSemiActiveGroups: [],
        semiActive: false,
        active: false,
    }),

    methods: {
        refreshGroupState(options) {
            let groups = Object.assign({}, this.groups);

            Object.keys(options).forEach((key) => {
                groups[key] = {active: this.groupActive(key), semiActive: this.groupSemiActive(key)};
            });

            this.groups = groups;

            this.refreshFieldState();
        },

        groupActive(groupName) {
            let activeItems = this.value[groupName].values.filter(item => item.checked === true);

            return activeItems.length === this.value[groupName].values.length;
        },

        groupSemiActive(groupName) {

            if (this.value[groupName]) {
                let activeItems = this.value[groupName].values.filter(item => item.checked === true);

                return activeItems.length > 0 && activeItems.length < this.value[groupName].values.length;
            }
        },

        refreshFieldState() {
            let semiActiveGroups = [];
            let activeGroups = [];

            Object.keys(this.groups).forEach((key) => {
                if (this.groups[key].semiActive === true) {
                    semiActiveGroups.push(key);
                }

                if (this.groups[key].active === true) {
                    activeGroups.push(key);
                }
            });

            this.determineFieldState(activeGroups, semiActiveGroups);
        },

        determineFieldState(activeGroups, semiActiveGroups) {
            let totalGroupCount = Object.keys(this.groups).length;
            let activeGroupCount = activeGroups.length;

            this.semiActive = this.fieldIsSemiActive(semiActiveGroups, activeGroupCount, totalGroupCount);
            this.active = activeGroupCount === totalGroupCount;
        },

        fieldIsSemiActive(semiActiveGroups, activeGroupCount, totalGroupCount) {
            let semiActiveGroupCount = semiActiveGroups.length;

            return semiActiveGroupCount > 0 && semiActiveGroupCount !== totalGroupCount ||
                activeGroupCount > 0 && totalGroupCount !== activeGroupCount
        }
    }
}
