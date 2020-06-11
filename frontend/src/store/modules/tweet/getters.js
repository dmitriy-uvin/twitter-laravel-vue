import moment from 'moment';

export default {
    tweetsSortedByCreatedDateDesc: state => Object.values(state.tweets).sort(
        (a, b) => (
            moment(b.created) - moment(a.created)
        )
    ),
    tweetsSortedByCreatedDateAsc: state => Object.values(state.tweets).sort(
        (a, b) => (
            moment(a.created) - moment(b.created)
        )
    ),

    tweetsSortedByLikesCountAsc: state => Object.values(state.tweets).sort(
        (a, b) => (
            a.likesCount - b.likesCount
        )
    ),
    tweetsSortedByLikesCountDesc: state => Object.values(state.tweets).sort(
        (a, b) => (
            b.likesCount - a.likesCount
        )
    ),

    getTweetById: state => id => state.tweets[id],

    isTweetOwner: (state, getters) => (tweetId, userId) => getters.getTweetById(tweetId).author.id === userId,

    tweetIsLikedByUser: (state, getters) => (tweetId, userId) => getters
        .getTweetById(tweetId)
        .likes
        .find(like => like.userId === userId) !== undefined,
};
