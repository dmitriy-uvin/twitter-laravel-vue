<template>
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-mobile is-centered">
                    <div class="column is-three-quarters-mobile is-two-thirds-tablet is-one-third-desktop">
                        <div class="box shadow-box">
                            <form
                                class="form"
                                @submit.prevent
                                novalidate="true"
                            >
                                <b-field label="Email">
                                    <b-input
                                        v-model="user.email"
                                        name="email"
                                        autofocus
                                    />
                                </b-field>

                                <div class="has-text-centered">
                                    <button
                                        type="button"
                                        class="button is-primary is-rounded"
                                        @click="onSendResetLink"
                                    >
                                        Send Reset Password Link
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { mapActions } from 'vuex';
import showStatusToast from '../components/mixin/showStatusToast';

export default {
    name: 'ForgotPassword',

    mixins: [showStatusToast],

    data: () => ({
        user: {
            email: '',
        },
    }),

    methods: {
        ...mapActions('auth', [
            'forgotPassword'
        ]),
        onSendResetLink() {
            this.forgotPassword(this.user.email)
                .then(() => {
                    this.showSuccessMessage('Email with reset link was sent!');
                })
                .catch(error => this.showErrorMessage(error.message));
        },
    },
};
</script>

<style scoped>
</style>
