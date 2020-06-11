<template>
    <div>
        <div class="card-image">
            <figure
                v-if="tweet.imageUrl"
                class="image is-3by1 tweet-image"
            >
                <img :src="tweet.imageUrl" alt="Tweet image">
            </figure>
        </div>
        <div class="card-content">
            <div class="media">
                <div class="media-left">
                    <figure class="media-left">
                        <router-link
                            v-if="tweet.author.avatar"
                            class="image is-64x64 is-square"
                            :to="{ name: 'user-page', params: { id: tweet.author.id } }"
                        >
                            <img class="is-rounded fit" :src="tweet.author.avatar" alt="Author avatar">
                        </router-link>

                        <router-link v-else :to="{ name: 'user-page', params: { id: tweet.author.id } }">
                            <DefaultAvatar class="image is-64x64" :user="tweet.author" />
                        </router-link>
                    </figure>
                </div>
                <div class="media-content">
                    <p class="title is-4">{{ tweet.author.firstName }} {{ tweet.author.lastName }}</p>
                    <p class="subtitle is-6">@{{ tweet.author.nickname }}</p>
                </div>
            </div>

            <div class="content">
                {{ tweet.text }}
                <br>

                <time><small class="created">{{ tweet.created | createdDate }}</small></time>
                <nav class="level is-mobile">
                    <div class="level-left auto-cursor">
                        <a class="level-item auto-cursor">
                            <span
                                class="icon is-medium has-text-info"
                                :class="{ 'has-text-danger': tweet.isCommented }"
                            >
                                <font-awesome-icon icon="comments" />
                            </span>
                            {{ tweet.commentsCount }}
                        </a>
                        <a class="level-item auto-cursor">
                            <span
                                class="icon is-medium has-text-info"
                                :class="{ 'has-text-danger': tweetIsLikedByUser(tweet.id, user.id) }"
                            >
                                <font-awesome-icon icon="heart" />
                            </span>
                            {{ tweet.likesCount }}
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import DefaultAvatar from '../../common/DefaultAvatar.vue';

export default {
    name: 'CardsView',
    components: {
        DefaultAvatar
    },
    props: {
        tweet: {
            type: Object,
            required: true,
        }
    },
    computed: {
        ...mapGetters('auth', {
            user: 'getAuthenticatedUser'
        }),
        ...mapGetters('tweet', [
            'tweetIsLikedByUser',
        ])
    },
};
</script>

<style lang="scss" scoped>
    @import '../../../styles/common';

    .tweet {
        cursor: pointer;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 5px 5px 5px 0 #00000020;
        transition: 0.2s ease-out all;


        &:hover {
            box-shadow: 1px 1px 0 0 #00000020;
        }

        &-image {
            margin: 12px 0 0 0;

            img {
                width: 100%;
            }
        }

        .nickname {
            margin-left: 5px;
        }

        .created {
            margin-left: 5px;
        }
    }

    .card-image figure{
        min-height: 160px;
    }

</style>
