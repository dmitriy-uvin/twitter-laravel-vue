import api from '@/api/Api';
import { commentMapper } from '@/services/Normalizer';
import { SET_LOADING } from '../../mutationTypes';
import {
    SET_COMMENTS,
    ADD_COMMENT,
    DELETE_COMMENT,
    UPDATE_COMMENT,
    SET_COMMENT_IMAGE,
    LIKE_COMMENT,
    DISLIKE_COMMENT
} from './mutationTypes';
import { DECREMENT_COMMENTS_COUNT, INCREMENT_COMMENTS_COUNT } from '../tweet/mutationTypes';

export default {
    async fetchComments({ commit }, tweetId) {
        commit(SET_LOADING, true, { root: true });
        try {
            const comments = await api.get(`/tweets/${tweetId}/comments`, {
                direction: 'asc',
            });

            commit(SET_COMMENTS, comments);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async fetchAllComments({ commit }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const comments = await api.get('/comments');

            commit(SET_COMMENTS, comments);
            commit(SET_LOADING, false, { root: true });
            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async addComment({ commit }, { tweetId, text }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const comment = await api.post('/comments', { tweet_id: tweetId, body: text });

            commit(ADD_COMMENT, comment);
            commit(`tweet/${INCREMENT_COMMENTS_COUNT}`, tweetId, { root: true });
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(commentMapper(comment));
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async deleteComment({ commit, dispatch }, comment) {
        commit(SET_LOADING, true, { root: true });

        try {
            await api.delete(`/comments/${comment.id}`);

            commit(DELETE_COMMENT, comment);
            dispatch('fetchComments', comment.tweetId);
            commit(`tweet/${DECREMENT_COMMENTS_COUNT}`, comment.tweetId, { root: true });
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },
    async editComment({ commit }, { id, body }) {
        commit(SET_LOADING, true, { root: true });

        try {
            await api.put(`/comments/${id}`, { body });

            commit(UPDATE_COMMENT, { id, body });
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async uploadCommentImage({ commit }, { id, imageFile }) {
        commit(SET_LOADING, true, { root: true });
        try {
            const formData = new FormData();
            formData.append('image', imageFile);
            const comment = await api.post(`/comments/${id}/image`, formData);

            commit(SET_COMMENT_IMAGE, {
                id,
                imageUrl: comment.image_url
            });

            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },
    async likeOrDislikeComment({ commit }, { id, userId }) {
        commit(SET_LOADING, true, { root: true });
        try {
            const response = await api.put(`/comments/${id}/like`);

            if (response.status === 'added') {
                commit(LIKE_COMMENT, {
                    id,
                    userId
                });
            } else {
                commit(DISLIKE_COMMENT, {
                    id,
                    userId
                });
            }

            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    }
};
