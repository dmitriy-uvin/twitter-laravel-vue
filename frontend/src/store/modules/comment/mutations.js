import { commentMapper } from '@/services/Normalizer';
import { SET_COMMENTS, ADD_COMMENT, DELETE_COMMENT } from './mutationTypes';

export default {
    [SET_COMMENTS]: (state, comments) => {
        let commentsByIdMap = {};

        comments.forEach(comment => {
            commentsByIdMap = {
                ...commentsByIdMap,
                [comment.id]: commentMapper(comment)
            };
        });

        state.comments = commentsByIdMap;
    },

    [ADD_COMMENT]: (state, comment) => {
        state.comments = {
            ...state.comments,
            [comment.id]: commentMapper(comment)
        };
    },
    [DELETE_COMMENT]: (state, comment) => {
        delete state.comments[comment.id];
    }
};
