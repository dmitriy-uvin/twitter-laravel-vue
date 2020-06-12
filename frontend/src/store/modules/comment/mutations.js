import { commentMapper } from '@/services/Normalizer';
import {
    SET_COMMENTS,
    ADD_COMMENT,
    DELETE_COMMENT,
    UPDATE_COMMENT,
    SET_COMMENT_IMAGE,
    LIKE_COMMENT,
    DISLIKE_COMMENT
} from './mutationTypes';

export default {
    [SET_COMMENTS]: (state, comments) => {
        let commentsByIdMap = {};

        comments.forEach(comment => {
            commentsByIdMap = {
                ...commentsByIdMap,
                [comment.id]: commentMapper(comment)
            };
        });
        state.comments = { ...state.comments, ...commentsByIdMap };
    },

    [ADD_COMMENT]: (state, comment) => {
        state.comments = {
            ...state.comments,
            [comment.id]: commentMapper(comment)
        };
    },

    [DELETE_COMMENT]: (state, comment) => {
        delete state.comments[comment.id];
    },

    [UPDATE_COMMENT]: (state, { id, body }) => {
        state.comments[id].body = body;
        commentMapper(state.comments[id]);
    },

    [SET_COMMENT_IMAGE]: (state, { id, imageUrl }) => {
        state.comments[id].imageUrl = imageUrl;
    },

    [LIKE_COMMENT]: (state, { id, userId }) => {
        state.comments[id].likesCount++;

        state.comments[id].likes.push({ userId });
    },

    [DISLIKE_COMMENT]: (state, { id, userId }) => {
        state.comments[id].likesCount--;

        state.comments[id].likes = state.comments[id].likes.filter(like => like.userId !== userId);
    }
};
