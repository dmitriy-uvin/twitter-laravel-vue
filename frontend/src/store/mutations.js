import { userMapper } from '@/services/Normalizer';
import {
    SET_LOADING,
    SET_LIKED_USERS
} from './mutationTypes';


export default {
    [SET_LOADING]: (state, isLoading = true) => {
        state.isLoading = isLoading;
    },

    [SET_LIKED_USERS]: (state, users) => {
        state.likedUsers = users.map(user => userMapper(user));
    }
};
