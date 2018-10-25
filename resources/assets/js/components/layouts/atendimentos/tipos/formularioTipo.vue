<template>
    <s-formcard titulo="Cadastro do Tipo de Atendimento" icon="fas fa-tag" @submitForm="validaForm" @resetForm="resetForm">
        <span slot="form">
            <s-formulario @submit="validaForm"  :action="action" :token="token"  ref="form"
                                    :method="method" id="criarTipo">
                <div class="offset-md-2 col-md-8 col-sm-12">
                    <s-input id="descricao"  type="text" placeholder="Descrição" :valor="descricao"
                            ref="campoDescricao" validate='required|alpha_spaces|min:3|max:100'
                            @validado="descricaoValido = $event" :maxlength='100' :autofocus="true"/>
                </div>
            </s-formulario>
        </span>
    </s-formcard>
</template>
<script>
export default {
    props: {
        action: {
            type: String,
            required: true
        },
        token: {
            type: String,
            required: true
        },
        method: {
            type: String,
            required: true
        },
        descricao: {
            type: String,
            default: ''
        }
    },
    data: function () {
        return {
            descricaoValido: false,
            clickSubmit: false
        }
    },
    methods: {
        validaForm: function() {
            this.$refs.campoDescricao.isValid();   
            this.clickSubmit = true;
        },
        resetForm: function() {
            this.$refs.campoDescricao.clear();
            this.$refs.campoDescricao.$validator.reset(); 
            this.$refs.campoDescricao.setFocus();    
        },
        isValid() {
            if(this.clickSubmit && this.descricaoValido) {
                    this.$refs.form.$el.submit();
            }
                     
            this.clickSubmit = false;
        }
    },
    watch: {
        clickSubmit: function() {
            if(this.clickSubmit)
                this.isValid()
        }
    }
}
</script>
