<template>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Edit Comment</p>
        </header>

        <section class="modal-card-body">
            <div class="error has-text-danger" v-if="errorMessage">{{ errorMessage }}</div>

            <b-field label="Comment Body">
                <b-input type="textarea" v-model="text" :placeholder="comment.body" />
            </b-field>

            <b-field class="file">
                <b-upload v-model="image">
                    <a class="button is-primary">
                        <b-icon pack="fa" icon="upload" />
                        <span>Edit image</span>
                    </a>
                </b-upload>
                <span class="file-name" v-if="image">
                    {{ image.name }}
                </span>
            </b-field>
        </section>

        <footer class="modal-card-foot">
            <b-button type="is-primary" :disabled="!text.trim()">
                Save
            </b-button>
        </footer>
    </div>
</template>

<script>
export default {
    name: 'EditCommentForm',
    props: {
        comment: {
            type: Object,
            required: true
        }
    },
    data: () => ({
        text: '',
        errorMessage: '',
        image: null
    }),
};
</script>

<style scoped>

</style>
