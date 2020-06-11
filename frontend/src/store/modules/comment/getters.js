import moment from 'moment';

export default {
    getCommentsSortedByCreatedDateAsc: state => Object
        .values(state.comments)
        .sort(
            (a, b) => (
                moment(a.created).isBefore(moment(b.created)) ? -1 : 1
            )
        ),

    getCommentsByTweetId: (state, getters) => tweetId => getters
        .getCommentsSortedByCreatedDateAsc
        .filter(comment => comment.tweetId === tweetId),

    isCommentOwner: () => (authorId, userId) => authorId === userId,

    commentIsLikedByUser: (state) => (commentId, userId) => state
        .comments[commentId].likes.find(like => like.userId === userId) !== undefined,

    commentIsUpdated: () => (comment) => (comment.created !== comment.updated) && comment.updated !== null,

};
