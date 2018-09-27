<template>
    <s-card :titulo="titulo" :footer="true"  :icon="icon">
        <span slot="body">
            <div  class="text-left">
               <slot name="form"></slot>
            </div>
        </span>
        <span slot="footer">
            <slot name="botoes">
                <button id="salvar" type="button" @click.prevent="validaForm" class="btn btn-outline-primary"> 
                    <i class="fas fa-save"></i> Salvar 
                </button>
                <button id="limpar" type="button" @click.prevent="resetForm" class="btn btn-outline-secondary">
                    <i class="fas fa-eraser"></i> Limpar
                </button>
            </slot>
        </span>
    </s-card>
</template>
<script>
export default {
    props: {
        titulo: {
            type: String,
            required: true
        },
        icon: {
            type: String
        }
    },
    methods: {
        validaForm: function () {
            this.$emit('submitForm')
        },
        resetForm: function () {
            this.$emit('resetForm')
        }
    }
}
</script>
