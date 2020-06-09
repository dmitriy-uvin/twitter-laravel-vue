<template>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Share tweet</p>
        </header>

        <section class="modal-card-body tile is-parent is-12">
            <article class="media tile is-child">
                <b-field>
                    <b-input
                        v-model="shareLink"
                        type="text"
                        icon-right="copy"
                        icon-right-clickable
                        @icon-right-click="copyLink"
                        readonly
                        id="shareLink"
                    />
                </b-field>
            </article>
        </section>

        <footer class="modal-card-foot">
            <b-button type="is-primary" outlined @click="$parent.close()">
                Close
            </b-button>
        </footer>
    </div>
</template>

<script>
import showStatusToast from '../../mixin/showStatusToast';

export default {
    name: 'ShareModal',
    mixins: [showStatusToast],
    data: () => ({
        shareLink: '',
    }),
    props: {
        tweet: {
            type: Object,
            required: true
        }
    },
    methods: {
        copyLink() {
            this.showDefaultMessage('Copied!');
            document.getElementById('shareLink').select();
            document.execCommand('copy');
        }
    },
    mounted() {
        this.shareLink = `${process.env.VUE_APP_URL}/tweets/${this.tweet.id}`;
    }
};
</script>

<style scoped>
</style>
