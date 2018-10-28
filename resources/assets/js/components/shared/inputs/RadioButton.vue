<template>
    <label :class="{'check': true, 'pb0': pb0 }">{{label}}
        <input type="radio" :name="name" ref="campoRadio" :value="value" @click="checked"  :checked="ischecked ? 'checked' : ''">
        <span class="checkmark"></span>
    </label>
</template>
<script>
    export default {
        props: {
            label: {
                type: String,
                default: ""
            },
            name: {
                type: String,
                required: true
            },
            id: {
                type: String,
                required: true
            },
            ischecked: {
                type: Boolean,
                default: false
            },
            value: {
                type: String,
                required: true
            },
            pb0: {
                required: false,
                type: Boolean,
                default: false
            }
        },
        methods: {
            checked ( ) {
                this.$emit('checked', this.value);
            },
            reset: function() {
                this.$refs.campoRadio.checked = false;
            }
        }
    }
</script>
<style scoped>

.checkmark {
  border-radius: 50%;
}
</style>
<style>
    .pb0{
        padding-bottom: 0px !important;
    }
</style>

