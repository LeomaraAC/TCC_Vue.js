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
                <button v-if="linkTabela != ''" id="telaListagem" type="button" @click="navigate" class="btn btn-outline-secondary">
                    <i class="fas fa-clipboard-list"></i> Voltar para listagem
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
        },
        linkTabela: {
            type: String,
            default: ''
        }
    },
    methods: {
        validaForm: function () {
            this.$emit('submitForm')
        },
        resetForm: function () {
            this.$emit('resetForm')
        },
        navigate: function() {
            window.location = this.linkTabela;
        }
    }
}
</script>
