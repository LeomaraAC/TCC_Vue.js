<template>
     <modal :name="name" height="auto" :draggable="true" :width="width"
                @before-open="beforeOpen" @before-close="beforeClose">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>{{title}}</strong></h5>
                <button type="button" class="close" @click="hide">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <slot name="modal-body"></slot>
            </div>
            <div class="modal-footer">
                <slot name="modal-footer"></slot>
            </div>
        </div>
    </modal>
</template>

<script>
    export default {
        props:{
            title:{
                type: String,
                required: true
            },
            name:{
                type: String,
                required: true
            },
            width: {
                type: Number,
                default: 600
            }
        },
         methods: {
            hide () {
                this.$modal.hide(this.name);
            },
            beforeOpen () {
                this.$emit('open');
            },
            beforeClose () {
                this.$emit('beforeClose');
            }
        }
    }
</script>
