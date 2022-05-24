export default {
    methods: {
        selectAll(event) {
            let options = Object.assign({}, this.value);

            Object.keys(options).forEach((key) => {
                options[key].values.forEach((item) => {
                    item.checked = !!event.target.checked;
                });
            });

            this.value = options;

            this.refreshGroupState(this.value);
        },

        selectGroup(event, groupName) {
            let options = Object.assign({}, this.value[groupName]);

            options.values.forEach((item) => {
                item.checked = !!event.target.checked;
            });

            this.value[groupName] = options;

            this.refreshGroupState(this.value);
        },
    }
}
