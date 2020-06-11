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
    async fetchComments({ commit }, { tweetId, page }) {
        commit(SET_LOADING, true, { root: true });
        try {
            const comments = await api.get(`/tweets/${tweetId}/comments`, {
                page,
                direction: 'asc',
            });

            commit(SET_COMMENTS, comments);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(comments);
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
    async likeOrDislikeComment({ commit, dispatch }, { comment, liker, receiver }) {
        commit(SET_LOADING, true, { root: true });
        try {
            const response = await api.put(`/comments/${comment.id}/like`);

            if (response.status === 'added') {
                commit(LIKE_COMMENT, {
                    id: comment.id,
                    userId: liker.id
                });
                dispatch('sendNotification', { comment, liker, receiver });
            } else {
                commit(DISLIKE_COMMENT, {
                    id: comment.id,
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
    async sendNotification(context, { comment, liker, receiver }) {
        try {
            if (receiver.notifications && receiver.id !== liker.id) {
                const formData = new FormData();
                formData.append('receiver', receiver.id);
                formData.append('liker', liker.id);
                formData.append('liked_entity_id', comment.id);
                formData.append('type', 'comment');

                api.post(`/users/${receiver.id}/notification`, formData);
            }

            return Promise.resolve();
        } catch (error) {
            return Promise.reject(error);
        }
    }
};
