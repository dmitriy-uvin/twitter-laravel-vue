<template>
    <div class="feed-container">
        <div class="navigation">
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
            <div class="buttons">
                <button
                    class="btn-left"
                    :class="{ 'btn-active': cardsViewSeen }"
                    @click="changeViewToCards"
                >
                    <img src="https://img.icons8.com/ios-filled/24/000000/health-data.png" />
                </button>
                <button
                    class="btn-right"
                    :class="{ 'btn-active': mediaViewSeen }"
                    @click="changeViewToMedia"
                >
                    <img src="https://img.icons8.com/ios-filled/24/000000/menu.png" />
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
        cardsViewSeen: false
    }),

    async created() {
        try {
            await this.fetchTweets({
                page: 1
            });
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
        ...mapGetters('tweet', {
            tweets: 'tweetsSortedByCreatedDate'
        }),
    },

    methods: {
        ...mapActions('tweet', [
            'fetchTweets',
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
    },
    mounted() {
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
        },
    }

};
</script>

<style lang="scss" scoped>
@import '~bulma/sass/utilities/initial-variables';

.buttons {
    float: right;
}
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

</style>
