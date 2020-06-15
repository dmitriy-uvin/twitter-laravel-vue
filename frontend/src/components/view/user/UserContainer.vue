<template>
    <div class="user-container">
        <b-field label="Type of tweets:">
            <b-select placeholder="Type of tweets:" v-model="tweetsType">
                <option value="all">
                    All Tweets
                </option>
                <option value="liked">
                    Liked Tweets
                </option>
            </b-select>
        </b-field>

        <TweetPreviewList
            :tweets="tweets"
            :loading-id="loadingId"
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
        loadingId: +new Date(),
        tweetsType: 'all'
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
        }),
    },
    methods: {
        ...mapActions('tweet', [
            'fetchTweetsByUserId',
            'fetchLikedTweetsByUserId'
        ]),
        async infiniteHandler($state) {
            try {
                let tweets;
                if (this.tweetsType === 'all') {
                    tweets = await this.fetchTweetsByUserId({
                        userId: this.$route.params.id,
                        params: {
                            page: this.page + 1
                        }
                    });
                }

                if (this.tweetsType === 'liked') {
                    tweets = await this.fetchLikedTweetsByUserId({
                        userId: this.$route.params.id,
                        params: {
                            page: this.page + 1
                        }
                    });
                }

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
    },
    watch: {
        async tweetsType(newTweetsType) {
            this.tweets = [];
            this.page = 1;
            this.loadingId += 1;
            if (newTweetsType === 'all') {
                this.tweets = await this.fetchTweetsByUserId({
                    userId: this.$route.params.id,
                    params: {
                        page: 1
                    }
                });
            }

            if (newTweetsType === 'liked') {
                this.tweets = await this.fetchLikedTweetsByUserId({
                    userId: this.$route.params.id,
                    params: {
                        page: 1
                    }
                });
            }
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
