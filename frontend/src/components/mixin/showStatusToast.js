export default {
    methods: {
        showErrorMessage(message) {
            this.$buefy.toast.open({
                message,
                type: 'is-danger',
            });
        },

        showSuccessMessage(message) {
            this.$buefy.toast.open({
                message,
                type: 'is-success',
            });
        },

        showDefaultMessage(message) {
            this.$buefy.toast.open({
                message
            });
        }
    }
};
