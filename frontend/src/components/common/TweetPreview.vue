<template>
    <div
        class="box tweet"
        :class="{ 'col-thirds': cardsViewSeen }"
        @click="$emit('click', tweet)"
    >
        <CardsView v-if="cardsViewSeen" :tweet="tweet" />
        <MediaView v-if="mediaViewSeen" :tweet="tweet" />
    </div>
</template>

<script>
import CardsView from '../view/feed/CardsView.vue';
import MediaView from '../view/feed/MediaView.vue';

export default {
    name: 'TweetPreview',

    components: {
        CardsView,
        MediaView
    },

    props: {
        tweet: {
            type: Object,
            required: true,
        },
        mediaViewSeen: {
            type: Boolean,
        },
        cardsViewSeen: {
            type: Boolean,
        }
    },
};
</script>
<style lang="scss" scoped>
    @import '../../styles/common';

    .col-thirds {
        width: 32%;
        display: inline-block;
        margin-left: 1%;
        vertical-align: top;
    }
    .card-image {
        min-height: 160px;
    }
    .card-image figure {
        min-height: 160px;
    }
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
                width: auto;
            }
        }

        .nickname {
            margin-left: 5px;
        }

        .created {
            margin-left: 5px;
        }
    }
    @media (min-width: 500px) and (max-width: 768px) {
        .col-thirds {
            width: 49%;
            display: inline-block;
            margin-left: 1%;
        }
    }
    @media (max-width: 499px) {
        .col-thirds {
            width: 100%;
        }
    }
</style>
