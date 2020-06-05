<template>
    <article class="media">
        <figure class="media-left">
            <router-link
                v-if="comment.author.avatar"
                class="image is-48x48 is-square"
                :to="{ name: 'user-page', params: { id: comment.author.id } }"
            >
                <img class="is-rounded fit" :src="comment.author.avatar">
            </router-link>

            <router-link v-else :to="{ name: 'user-page', params: { id: comment.author.id } }">
                <DefaultAvatar class="image is-48x48" :user="comment.author" />
            </router-link>
        </figure>
        <div class="media-content">
            <div class="content">
                <p>
                    <strong>
                        {{ comment.author.firstName }} {{ comment.author.lastName }}
                    </strong>
                    <b-dropdown
                        aria-role="menu"
                        position="is-bottom-left"
                        class="comment-menu"
                        v-if="isCommentOwner(comment.author.id, user.id)"
                    >
                        <p slot="trigger" role="button">
                            <b-icon pack="fa" icon="ellipsis-h" />
                        </p>
                        <b-dropdown-item aria-role="menuitem">
                            <b-icon pack="fa" icon="edit" />
                            <button class="dropdown-item-text" @click="onEditComment">Edit</button>
                        </b-dropdown-item>

                        <hr class="dropdown-divider">

                        <b-dropdown-item aria-role="menuitem">
                            <b-icon pack="fa" icon="trash" />
                            <button class="dropdown-item-text" @click="onDeleteComment">Delete</button>
                        </b-dropdown-item>
                    </b-dropdown>
                    <br>
                    {{ comment.body }}
                    <br>
                    <small class="has-text-grey">
                        {{ comment.created | createdDate }}
                    </small>
                </p>
            </div>
        </div>
        <b-modal :active.sync="isEditCommentModalActive" has-modal-card>
            <EditCommentForm :comment="comment"/>
        </b-modal>
    </article>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import DefaultAvatar from '../../common/DefaultAvatar.vue';
import EditCommentForm from './EditCommentForm.vue';
import showStatusToast from '../../mixin/showStatusToast';

export default {
    name: 'Comment',
    components: {
        DefaultAvatar,
        EditCommentForm
    },
    data: () => ({
        isEditCommentModalActive: false,
    }),
    mixins: [showStatusToast],
    computed: {
        ...mapGetters('auth', {
            user: 'getAuthenticatedUser'
        }),
        ...mapGetters('comment', [
            'isCommentOwner'
        ])
    },
    props: {
        comment: {
            type: Object,
            required: true,
        }
    },
    methods: {
        ...mapActions('comment', [
            'deleteComment'
        ]),
        onEditComment() {
            this.isEditCommentModalActive = true;
        },
        onDeleteComment() {
            this.$buefy.dialog.confirm({
                title: 'Deleting comment',
                message: 'Are you sure you want to <b>delete</b> your comment? This action cannot be undone.',
                confirmText: 'Delete comment',
                type: 'is-danger',
                onConfirm: async () => {
                    try {
                        const currentTweetId = this.comment.tweetId;
                        await this.deleteComment(this.comment);
                        this.showSuccessMessage('Comment deleted!');
                        this.$router.push({ name: 'tweet-page', params: { id: currentTweetId } }).catch(() => {});
                    } catch {
                        this.showErrorMessage('Unable to delete comment!');
                    }
                }
            });
        }
    },
};
</script>

<style lang="scss" scoped>

.comment-menu {
    float: right;
    cursor: pointer;
}
.dropdown-item-text{
    font-size: 16px;
    margin-left: 5px;
    vertical-align: 15%;
}
nav {
    margin-left: -8px;
}
button {
    outline: none;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}
.content {
    p {
        margin-bottom: 0;
    }
}
</style>
