<template>
<div class="form-group mb-3">
    <div class="input-group">
        <slot>
            <input ref="input"
                :id="id"
                :name="id"
                :type="type"
                :placeholder="placeholder"  
                v-validate="validate"
                :class="{'form-control form-control-warning': true, 'is-invalid': errors.has(id) }"
                data-vv-validate-on="focusout|input"
                v-model="input"
                :autofocus="autofocus"
                :maxlength="maxlength"
            >
        </slot>
        <div class="input-group-append" v-if="icon && btn" id="btn" @click="cliqueBtn">
            <span class="input-group-text"><i v-bind:class="icon"></i></span>
        </div>
        <div class="input-group-append" v-if="icon && !btn">
            <span class="input-group-text"><i v-bind:class="icon"></i></span>
        </div>
    </div>
    <div class="erro pl-1">
        <slot name="error">
            {{ errors.first(id) }}
        </slot>
    </div>
    </div>
</template>

<script>
    export default {
        props: {
            icon: {
                type: String,
                default: ''
            },
            validate: {
                type: String,
                default: ''
            },
            id: {
                type: [String, Number],
                default: ''
            },
            placeholder: {
                type: String,
                default: ''
            },
            type: {
                type: String,
                default: 'text'
            },
            valor: {
                type: String,
                default: ''
            },
            autofocus: {
                type: Boolean,
                default: false
            },
            maxlength: {
                type: Number
            },
            btn: {
                type: Boolean,
                default: false
            }
        },
        data: function () {
            return {
                input: this.valor || ''
            }
        },
        methods: {
            isValid: function () {
                this.$validator.validateAll().then(result => {
                    if (result) 
                        this.$emit('validado', true);
                    else
                     this.$emit('validado', false);
                });
            },
            clear: function () {
                this.input = ''
            },
            setFocus: function () {
                this.$refs.input.focus();
            },
            cliqueBtn: function () {
                Event.fire('btnInput');
            }
        },
         watch: {
            input() {
                this.$emit('value', this.input);
            }
        }
    }
</script>
<style scoped>
    .input-group-text {
        background-color: white;
    }    
    #btn:hover{
        cursor: pointer;
    }
</style>