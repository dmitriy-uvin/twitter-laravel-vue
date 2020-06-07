import api from '@/api/Api';
import {
    SET_LOADING,
    SET_TWEET_LIKED_USERS
} from './mutationTypes';

export default {
    async getUsersByIds({ commit }, ids) {
        commit(SET_LOADING, true, { root: true });

        try {
            const usersLiked = await ids.map(async id => {
                const response = await api.get(`/users/${id}`);
                return response;
            });
            const receivedUsersLiked = await Promise.all(usersLiked);

            commit(SET_TWEET_LIKED_USERS, receivedUsersLiked);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    }
};
