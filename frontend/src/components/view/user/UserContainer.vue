<template>
    <div class="user-container">
        <div class="column">
            <b-button
                type="is-primary"
                icon-left="comments"
                @click="allTweets"
            >
                All Tweets
            </b-button>
        </div>
        <div class="column">
            <b-button
                type="is-warning"
                icon-left="heart"
                @click="getOnlyLiked"
            >
                Liked Tweets
            </b-button>
        </div>

        <TweetPreviewList
            :tweets="tweets"
            @infinite="infiniteHandler"
            :cards-view-seen="cardsViewSeen"
            :media-view-seen="mediaViewSeen"
        />
        <NoContent :show="noContent" title="No tweets yet :)" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import TweetPreviewList from '@/components/common/TweetPreviewList.vue';
import NoContent from '@/components/common/NoContent.vue';
import showStatusToast from '@/components/mixin/showStatusToast';

export default {
    name: 'UserContainer',

    mixins: [showStatusToast],

    components: {
        TweetPreviewList,
        NoContent
    },

    data: () => ({
        tweets: [],
        page: 1,
        noContent: false,
        mediaViewSeen: null,
        cardsViewSeen: null,
        onlyLiked: false
    }),

    async created() {
        try {
            this.tweets = await this.fetchTweetsByUserId({
                userId: this.$route.params.id,
                params: {
                    page: 1
                }
            });
            if (!this.tweets.length) {
                this.noContent = true;
            }
        } catch (error) {
            this.showErrorMessage(error.message);
        }
    },
    computed: {
        ...mapGetters('tweet', [
            'tweetIsLikedByUser'
        ]),
        ...mapGetters('auth', {
            user: 'getAuthenticatedUser'
        })
    },
    methods: {
        ...mapActions('tweet', [
            'fetchTweetsByUserId',
        ]),

        async infiniteHandler($state) {
            try {
                const tweets = await this.fetchTweetsByUserId({
                    userId: this.$route.params.id,
                    params: {
                        page: this.page + 1
                    }
                });

                if (tweets.length) {
                    this.tweets.push(...tweets);
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
        async getOnlyLiked() {
            this.tweets = this.tweets.filter(tweet => this.tweetIsLikedByUser(tweet.id, this.user.id));
        },
        async allTweets() {
            this.tweets = await this.fetchTweetsByUserId({
                userId: this.$route.params.id,
                params: {
                    page: 1
                }
            });
        }
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
};
</script>

<style scoped>
</style>
