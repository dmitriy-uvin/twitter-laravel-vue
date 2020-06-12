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
                                <b-field label="User Email">
                                    <b-input
                                        v-model="user.email"
                                        name="password"
                                        autofocus
                                        readonly
                                    />
                                </b-field>

                                <b-field label="New Password">
                                    <b-input
                                        v-model="user.password"
                                        type="password"
                                        name="password"
                                        autofocus
                                    />
                                </b-field>

                                <b-field label="Confirm Password">
                                    <b-input
                                        v-model="user.confirmPassword"
                                        type="password"
                                        name="confirmPassword"
                                        autofocus
                                    />
                                </b-field>

                                <div class="has-text-centered">
                                    <button
                                        type="button"
                                        class="button is-primary is-rounded"
                                        @click="onReset"
                                    >
                                        Reset
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
    name: 'ResetPassword',
    mixins: [showStatusToast],
    data: () => ({
        user: {
            email: '',
            confirmPassword: '',
            password: ''
        },
        token: ''
    }),
    created() {
        this.user.email = this.$route.params.email;
        this.token = this.$route.query.token;
    },
    methods: {
        ...mapActions('auth', [
            'resetPassword',
            'signIn'
        ]),
        onReset() {
            this.resetPassword({ user: this.user, token: this.token })
                .then(async () => {
                    this.showSuccessMessage('Password was successfully reseted!');
                    await this.signIn({ email: this.user.email, password: this.user.password });
                    this.$router.push({ name: 'feed' });
                })
                .catch(error => this.showErrorMessage(error.message));
        }
    }
};
</script>

<style scoped>

</style>
