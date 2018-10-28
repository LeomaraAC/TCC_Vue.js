<template>
    <label class="check ">
        {{label}}
        <input type="checkbox" :name="name" @click="checked" ref="campoCheckbox" :checked="ischecked ? 'checked' : ''">
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
            ischecked: {
                type: Boolean,
                required: true
            },
            item: {
                required: false,
                default: undefined
            }
        },
        methods: {
            checked ( ) {
                if(this.item == undefined)
                    this.$emit('checked', [!this.ischecked, this.name]);
                else
                    this.$emit('checked', [!this.ischecked, this.item])
            },
            reset: function() {
                this.$refs.campoCheckbox.checked = false;
            }
        }
    }
</script>

