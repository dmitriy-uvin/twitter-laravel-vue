<template>
    <div class="modal-card" @keyup.ctrl.exact.enter="save">
        <header class="modal-card-head">
            <p class="modal-card-title">Edit Comment</p>
        </header>

        <section class="modal-card-body">
            <div class="error has-text-danger" v-if="errorMessage">{{ errorMessage }}</div>

            <b-field label="Comment Body">
                <b-input type="textarea" v-model="body" :placeholder="comment.body" />
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
            <b-button type="is-primary" :disabled="!body.trim()" @click="save">
                Save
            </b-button>
        </footer>
    </div>
</template>

<script>
import { mapActions } from 'vuex';
import showStatusToast from '../../mixin/showStatusToast';

export default {
    name: 'EditCommentForm',
    props: {
        comment: {
            type: Object,
            required: true
        }
    },
    mixins: [showStatusToast],
    data: () => ({
        body: '',
        errorMessage: '',
        image: null
    }),
    methods: {
        ...mapActions('comment', [
            'editComment',
            'uploadCommentImage'
        ]),
        async save() {
            try {
                const comment = await this.editComment({ id: this.comment.id, body: this.body });

                if (comment && this.image === null) {
                    this.$parent.close();
                    return;
                }

                if (this.image !== null) {
                    await this.uploadCommentImage({
                        id: this.comment.id,
                        imageFile: this.image
                    });
                }


                this.$parent.close();
                this.showSuccessMessage('Comment updated!');
                this.$emit('updated');
            } catch (error) {
                this.showErrorMessage(error.message);
            }
        },
        showErrorMessage(msg) {
            this.errorMessage = msg;
        }
    }
};
</script>

<style scoped>

</style>
