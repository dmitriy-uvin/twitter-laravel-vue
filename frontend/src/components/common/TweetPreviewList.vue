<template>
    <div class="tweets-container">
        <transition-group name="slide-prev" tag="div">
            <template v-for="tweet in tweets">
                <TweetPreview
                    :key="tweet.id"
                    :tweet="tweet"
                    @click="onTweetClick"
                    :cards-view-seen="cardsViewSeen"
                    :media-view-seen="mediaViewSeen"
                />
            </template>
        </transition-group>
        <infinite-loading :identifier="loadingId" @infinite="infiniteHandler">
            <div slot="no-more" />
            <div slot="no-results" />
            <div slot="spinner" />
        </infinite-loading>
    </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading';
import TweetPreview from './TweetPreview.vue';

export default {
    name: 'TweetPreviewList',

    props: {
        tweets: {
            type: Array,
            required: true
        },
        mediaViewSeen: {
            type: Boolean,
        },
        cardsViewSeen: {
            type: Boolean,
        },
        loadingId: {
            type: Number
        }
    },
    components: {
        TweetPreview,
        InfiniteLoading,
    },

    methods: {
        onTweetClick(tweet) {
            this.$router.push({ name: 'tweet-page', params: { id: tweet.id } }).catch(() => {});
        },

        infiniteHandler($state) {
            this.$emit('infinite', $state);
        },
    },
};
</script>

<style lang="scss" scoped>
.tweets-container {
    padding-bottom: 20px;
}
</style>
