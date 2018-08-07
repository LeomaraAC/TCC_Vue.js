<template>
    <s-formcard :titulo="titulo" icon="fas fa-users-cog" @submitForm="validaForm" @resetForm="resetForm">
        <span slot="form">
            <s-formulario @submit="validaForm"  :action="action" :token="token"  ref="form"
                                    :method="method" id="criarUsuario">
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <s-input id="prontuario"  type="text" placeholder="Prontuário do usuário" ref="campoProntuario"
                            @validado="campoValido.prontuario = $event" validate='required|alpha_num|min:5|max:10' :maxlength='10'
                            :valor="this.valores.prontuario" :autofocus="true"></s-input>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <s-input id="nome"  type="text" placeholder="Nome do usuário" ref="campoNome"  @validado="campoValido.nome = $event"
                            validate='required|alpha_spaces|min:3|max:60'  :valor="this.valores.nome" :maxlength='60'></s-input>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <s-input id="email"  type="email" placeholder="Email do usuário" ref="campoEmail"  @validado="campoValido.email = $event"
                            validate='required|email|max:60'  :valor="this.valores.email" :maxlength='60'></s-input>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-5 col-sm-12">
                        <s-select ref="selectGrupo"
                            id="grupos"
                            :options="grupos"
                            placeholder="Grupos"
                            track-by="idGrupo"
                            label="nomeGrupo"
                            campo="grupo"
                            :required="true"
                            :selected = this.valores.grupo
                            @selected="setSelect"></s-select>
                    </div>
                </div>
            </s-formulario>
        </span>
    </s-formcard>
</template>
<script>

export default {
    props: {
        titulo: {
            required: true,
            type: String
        },
        icon: {
            type: String
        },
        grupos: {
            type: [Object, Array],
            required: true
        },
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
        valores: {
            type: Object,
            default: function () {
                return {
                    nome: '',
                    prontuario: '',
                    email: '',
                    grupo: ''
                }
            }
        }
    },
    data: function () {
        return { 
            select: {}
        }
    },
    methods: {
        setNome: function (value) {
            this.nome = value;
        },
        setSelect: function (value) {
            this.select = value;
        },
        enviarForm: function (params) {
            if(params && Object.keys(this.select).length != 0)
                this.$refs.form.$el.submit(); // Acessa o form que está em outro componente e faz o submit
        },
        validaForm: function () { 
            this.$refs.selectGrupo.onTouch();   
            this.$refs.campoNome.isValid();
        },
        resetForm: function () {
             this.$refs.campoProntuario.clear();
            this.$refs.campoProntuario.$validator.reset(); 
            this.$refs.campoNome.clear();
            this.$refs.campoNome.$validator.reset(); // Reseta os erros
            this.$refs.selectGrupo.reset();
            this.$refs.campoProntuario.setFocus();
        }
    }
}
</script>
