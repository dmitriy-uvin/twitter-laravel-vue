import api from '@/api/Api';
import { tweetMapper } from '@/services/Normalizer';
import {
    SET_TWEETS,
    SET_TWEET_IMAGE,
    SET_TWEET,
    DELETE_TWEET,
    LIKE_TWEET,
    DISLIKE_TWEET
} from './mutationTypes';
import { SET_LOADING } from '../../mutationTypes';

export default {
    async fetchTweets({ commit }, { page }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const tweets = await api.get('/tweets', { page });

            commit(SET_TWEETS, tweets);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(
                tweets.map(tweetMapper)
            );
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async fetchTweetsByUserId({ commit }, { userId, params }) {
        commit(SET_LOADING, true, { root: true });
        try {
            const tweets = await api.get(`/users/${userId}/tweets`, params);

            commit(SET_TWEETS, tweets);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(
                tweets.map(tweetMapper)
            );
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async fetchLikedTweetsByUserId({ commit }, { userId, params }) {
        commit(SET_LOADING, true, { root: true });
        try {
            const tweets = await api.get(`/users/${userId}/tweets/liked`, params);

            commit(SET_TWEETS, tweets);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(
                tweets.map(tweetMapper)
            );
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async fetchTweetById({ commit }, tweetId) {
        commit(SET_LOADING, true, { root: true });

        try {
            const tweet = await api.get(`/tweets/${tweetId}`);

            commit(SET_LOADING, false, { root: true });
            commit(SET_TWEET, tweet);

            return Promise.resolve(tweetMapper(tweet));
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async addTweet({ commit }, text) {
        commit(SET_LOADING, true, { root: true });

        try {
            const tweet = await api.post('/tweets', { text });

            commit(SET_TWEET, tweet);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(tweetMapper(tweet));
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async uploadTweetImage({ commit }, { id, imageFile }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const formData = new FormData();
            formData.append('image', imageFile);

            const tweet = await api.post(`/tweets/${id}/image`, formData);

            commit(SET_TWEET_IMAGE, {
                id,
                imageUrl: tweet.image_url
            });

            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async editTweet({ commit }, { id, text }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const tweet = await api.put(`/tweets/${id}`, { text });

            commit(SET_TWEET, tweet);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(tweetMapper(tweet));
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async deleteTweet({ commit }, id) {
        commit(SET_LOADING, true, { root: true });

        try {
            await api.delete(`/tweets/${id}`);

            commit(DELETE_TWEET, id);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async likeOrDislikeTweet({ commit, dispatch }, { tweet, liker, receiver }) {
        commit(SET_LOADING, true, { root: true });
        try {
            const data = await api.put(`/tweets/${tweet.id}/like`);

            if (data.status === 'added') {
                commit(LIKE_TWEET, {
                    id: tweet.id,
                    userId: liker.id
                });
                dispatch('sendNotification', { tweet, liker, receiver });
            } else {
                commit(DISLIKE_TWEET, {
                    id: tweet.id,
                    userId: liker.id
                });
            }

            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },
    async sendNotification(context, { tweet, liker, receiver }) {
        try {
            if (receiver.notifications && receiver.id !== liker.id) {
                const formData = new FormData();
                formData.append('receiver', receiver.id);
                formData.append('liker', liker.id);
                formData.append('liked_entity_id', tweet.id);
                formData.append('type', 'tweet');

                api.post(`/users/${receiver.id}/notification`, formData);
            }

            return Promise.resolve();
        } catch (error) {
            return Promise.reject(error);
        }
    }
};
