<template>
    <div class="feed-container">
        <div class="navigation columns is-12 is-vcentered">
            <div class="tile is-child is-2">
                <b-button
                    class="btn-add-tweet"
                    rounded
                    size="is-medium"
                    type="is-danger"
                    icon-left="twitter"
                    icon-pack="fab"
                    @click="onAddTweetClick"
                >
                    Tweet :)
                </b-button>
            </div>

            <div class="column is-3 column" />

            <div class="column is-2 column">
                <b-button
                    type="is-info"
                    icon-left="clock"
                    @click="sortByDate"
                >
                    Date:
                    <span v-if="sorting==='dateDesc'">ASC</span>
                    <span v-else>DESC</span>
                </b-button>
            </div>

            <div class="column is-2 column">
                <b-button
                    type="is-danger"
                    icon-left="heart"
                    @click="sortByLikes"
                >
                    Likes:
                    <span v-if="sorting==='likesDesc'">ASC</span>
                    <span v-else>DESC</span>
                </b-button>
            </div>
            <div class="column is-2" />
            <div class="column is-2">
                <button
                    class="btn-left"
                    :class="{ 'btn-active': cardsViewSeen }"
                    @click="changeViewToCards"
                >
                    <font-awesome-icon icon="th-large" size="2x" />
                </button>
                <button
                    class="btn-right"
                    :class="{ 'btn-active': mediaViewSeen }"
                    @click="changeViewToMedia"
                >
                    <font-awesome-icon icon="bars" size="2x" />
                </button>
            </div>
        </div>

        <TweetPreviewList
            :tweets="tweets"
            @infinite="infiniteHandler"
            :cards-view-seen="cardsViewSeen"
            :media-view-seen="mediaViewSeen"
        />

        <b-modal :active.sync="isNewTweetModalActive" has-modal-card>
            <NewTweetForm />
        </b-modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import TweetPreviewList from '@/components/common/TweetPreviewList.vue';
import { pusher } from '@/services/Pusher';
import { SET_TWEET } from '@/store/modules/tweet/mutationTypes';
import showStatusToast from '@/components/mixin/showStatusToast';
import NewTweetForm from './NewTweetForm.vue';

export default {
    name: 'FeedContainer',
    mixins: [showStatusToast],
    components: {
        TweetPreviewList,
        NewTweetForm,
    },
    data: () => ({
        isNewTweetModalActive: false,
        page: 1,
        mediaViewSeen: true,
        cardsViewSeen: false,
        sorting: 'dateAsc'
    }),
    async created() {
        try {
            await this.fetchTweets({
                page: 1
            });
            await this.fetchAllComments();
        } catch (error) {
            this.showErrorMessage(error.message);
        }
        const channel = pusher.subscribe('private-tweets');
        channel.bind('tweet.added', (data) => {
            this.$store.commit(`tweet/${SET_TWEET}`, data.tweet);
        });
    },
    beforeDestroy() {
        pusher.unsubscribe('private-tweets');
    },
    computed: {
        ...mapGetters('tweet', [
            'tweetsSortedByCreatedDateDesc',
            'tweetsSortedByCreatedDateAsc',
            'tweetsSortedByLikesCountDesc',
            'tweetsSortedByLikesCountAsc',
        ]),
        ...mapGetters('comment', [
            'getCommentsByTweetId'
        ]),
        // eslint-disable-next-line consistent-return,vue/return-in-computed-property
        tweets() {
            if (this.sorting === 'dateDesc') {
                return this.tweetsSortedByCreatedDateAsc;
            }
            if (this.sorting === 'dateAsc') {
                return this.tweetsSortedByCreatedDateDesc;
            }
            if (this.sorting === 'likesDesc') {
                return this.tweetsSortedByLikesCountAsc;
            }
            if (this.sorting === 'likesAsc') {
                return this.tweetsSortedByLikesCountDesc;
            }
        }
    },

    methods: {
        ...mapActions('tweet', [
            'fetchTweets',
        ]),
        ...mapActions('comment', [
            'fetchAllComments'
        ]),
        changeViewToMedia() {
            this.mediaViewSeen = true;
            this.cardsViewSeen = false;
        },
        changeViewToCards() {
            this.mediaViewSeen = false;
            this.cardsViewSeen = true;
        },

        onAddTweetClick() {
            this.showAddTweetModal();
        },

        showAddTweetModal() {
            this.isNewTweetModalActive = true;
        },

        async infiniteHandler($state) {
            try {
                const tweets = await this.fetchTweets({ page: this.page + 1 });
                if (tweets.length) {
                    this.page += 1;
                    $state.loaded();
                } else {
                    $state.complete();
                }
            } catch (error) {
                this.showErrorMessage(error.message);
                $state.complete();
            }
        },

        sortByDate() {
            if (this.sorting === 'dateDesc') {
                this.sorting = 'dateAsc';
            } else if (this.sorting === 'dateAsc' || this.sorting !== 'dateDesc') {
                this.sorting = 'dateDesc';
            }
        },
        sortByLikes() {
            if (this.sorting === 'likesDesc') {
                this.sorting = 'likesAsc';
            } else if (this.sorting === 'likesAsc' || this.sorting !== 'likesDesc') {
                this.sorting = 'likesDesc';
            }
        },
    },
    async mounted() {
        if (localStorage.getItem('mediaViewSeen') === 'true') {
            this.mediaViewSeen = true;
            this.cardsViewSeen = false;
        }
        if (localStorage.getItem('cardsViewSeen') === 'true') {
            this.mediaViewSeen = false;
            this.cardsViewSeen = true;
        }
    },

    watch: {
        mediaViewSeen(newMediaViewSeen) {
            localStorage.setItem('mediaViewSeen', newMediaViewSeen);
        },
        cardsViewSeen(newCardsViewSeen) {
            localStorage.setItem('cardsViewSeen', newCardsViewSeen);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~bulma/sass/utilities/initial-variables';

.btn-left, .btn-right {
    border: none;
    padding: 10px;
    outline: none;
}

.btn-left {
    border-radius: 5px 0 0 5px;
}

.btn-right{
    border-radius: 0 5px 5px 0;
}

.btn-active{
    background: #ff3860;
}

.navigation {
    padding: 10px 0;
    margin-bottom: 20px;
}

.modal-card {
    border-radius: 6px;
}

.btn-add-tweet {
    transition: 0.2s ease-out all;
    box-shadow: 1px 5px 5px 0 #00000020;

    &:hover {
        box-shadow: 1px 1px 0 0 #00000020;
    }

    @media screen and (max-width: $tablet) {
        font-size: 1rem;
    }
}
.sort-tag {
    font-weight: bold;
    font-size: 1.2rem;
}
</style>
