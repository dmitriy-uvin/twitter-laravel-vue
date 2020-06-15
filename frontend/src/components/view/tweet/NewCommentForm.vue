<template>
    <article class="media">
        <figure class="media-left">
            <p class="image is-48x48 is-square" v-if="user.avatar">
                <img class="is-rounded fit" :src="user.avatar">
            </p>
            <DefaultAvatar v-else class="image is-48x48" :user="user" />
        </figure>
        <div class="media-content">
            <div class="field">
                <p class="control">
                    <textarea
                        class="textarea"
                        v-model="text"
                        placeholder="Add a comment..."
                        @keyup.ctrl.exact.enter="onPostComment"
                    />
                </p>
            </div>

            <b-field class="file">
                <b-upload v-model="image">
                    <a class="button is-warning">
                        <b-icon pack="fa" icon="upload" />
                        <span>Upload image</span>
                    </a>
                </b-upload>
                <span class="file-name" v-if="image">
                    {{ image.name }}
                </span>
            </b-field>

            <div class="field">
                <p class="control">
                    <button
                        class="button is-primary"
                        :disabled="!text.trim()"
                        @click="onPostComment"
                    >
                        Post comment
                    </button>
                </p>
            </div>
        </div>
    </article>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import DefaultAvatar from '@/components/common/DefaultAvatar.vue';

export default {
    name: 'NewCommentForm',

    components: {
        DefaultAvatar,
    },

    props: {
        tweetId: {
            type: Number,
            required: true,
        },
    },

    data: () => ({
        text: '',
        image: null
    }),

    computed: {
        ...mapGetters('auth', {
            user: 'getAuthenticatedUser'
        }),
    },

    methods: {
        ...mapActions('comment', [
            'addComment',
            'uploadCommentImage'
        ]),

        clearInput() {
            this.text = '';
        },

        async onPostComment() {
            const comment = await this.addComment({
                tweetId: this.tweetId,
                text: this.text,
            });

            if (this.image !== null) {
                await this.uploadCommentImage({
                    id: comment.id,
                    imageFile: this.image
                });
            }

            this.image = '';
            this.clearInput();
        },
    },
};
</script>

<style lang="scss" scoped>
.textarea {
    min-height: 60px;
}
</style>
