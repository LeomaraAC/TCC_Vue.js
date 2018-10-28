<template>
    <s-formcard :titulo="titulo" icon="fas fa-users-cog" linkTabela="/master/usuarios"
                @submitForm="validaForm" @resetForm="resetForm">
        <span slot="form">
            <s-formulario @submit="validaForm"  :action="action" :token="token"  ref="form"
                                    :method="method" id="criarUsuario">
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <s-input id="prontuario"  type="text" placeholder="Prontuário do usuário" ref="campoProntuario"
                            @validado="campoValido.prontuario = $event" validate='required|alpha_num|min:5|max:10' :maxlength='10'
                            :valor="valores.prontuario" :autofocus="true"></s-input>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <s-input id="nome"  type="text" placeholder="Nome do usuário" ref="campoNome"  @validado="campoValido.nome = $event"
                            validate='required|alpha_spaces|min:3|max:60'  :valor="valores.nome" :maxlength='60'></s-input>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <s-input id="email"  type="email" placeholder="Email do usuário" ref="campoEmail"  @validado="campoValido.email = $event"
                            validate='required|email|max:60'  :valor="valores.email" :maxlength='60'></s-input>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-4 col-sm-12">
                        <s-select ref="selectGrupo"
                            id="grupos"
                            :options="grupos"
                            placeholder="Grupos"
                            track-by="idGrupo"
                            label="nomeGrupo"
                            campo="grupo"
                            :required="true"
                            :selected = valores.grupo
                            @selected="setSelect"></s-select>
                    </div>
                    <div class="col-md-4 col-sm-12" v-if="!editar">
                        <s-input>
                            <input ref="campoSenha" id="senha"
                                        name="senha" v-model="senha"
                                        type="password"
                                        placeholder="Senha"  
                                        v-validate="'required|min:6|max:100'"
                                        :class="{'form-control form-control-warning': true, 'is-invalid': errors.has('senha') }"
                                        data-vv-validate-on="focusout"
                                        maxlength="100"
                            >
                            <div slot="error">
                                {{ errors.first('senha') }}
                            </div>
                        </s-input>
                    </div>
                    <div class="col-md-4 col-sm-12" v-if="!editar">
                        <s-input>
                            <input ref="campoConfirmarSenha" id="senha_confirmation" name="senha_confirmation"
                                    type="password" v-model="confSenha"
                                    placeholder="Confirmar senha"  
                                    v-validate="{ required: true, is: senha }"
                                    :class="{'form-control form-control-warning': true, 'is-invalid': errors.has('senha_confirmation') }"
                                    data-vv-validate-on="focusout"
                                    maxlength="100"
                            >
                            <div slot="error">
                                {{ errors.first('senha_confirmation') }}
                            </div>
                        </s-input>
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
        editar: {
            type: Boolean,
            default: false
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
            select: {},
            campoValido: {
                prontuario: false,
                nome: false,
                email: false
            },
            clickSubmit: false,
            senha:'',
            confSenha:''
        }
    },
    mounted: function () {
        const dict = {
            custom: {
                senha_confirmation: {
                required: 'Essas senhas não coincidem.',
                is: 'Essas senhas não coincidem.'
                }
            }
        };

        this.$validator.localize('pt_BR', dict);
    },
    methods: {
        setSelect: function (value) {
            this.select = value;
        },
        enviarForm: function () {  
                this.$refs.form.$el.submit(); // Acessa o form que está em outro componente e faz o submit
        },
        validaForm: function () { 
            this.$refs.selectGrupo.onTouch();   
            this.$refs.campoProntuario.isValid();   
            this.$refs.campoNome.isValid();   
            this.$refs.campoEmail.isValid(); 
            this.clickSubmit = true;
        },
        resetForm: function () {
            //Prontuário
            this.$refs.campoProntuario.clear();
            this.$refs.campoProntuario.$validator.reset(); 
            //Nome
            this.$refs.campoNome.clear();
            this.$refs.campoNome.$validator.reset();
            //Email
            this.$refs.campoEmail.clear();
            this.$refs.campoEmail.$validator.reset(); 
            // //Senha
            this.senha = '';
            // //Confirmar Senha
            this.confSenha = '';
            // Resetar a validação da senha e confirma senha
            this.$validator.reset(); 
            //Grupo
            this.$refs.selectGrupo.reset();
            //Focus no campo Prontuário
            this.$refs.campoProntuario.setFocus();           
        },
        isValid() {
            if(this.clickSubmit && this.campoValido.prontuario && this.campoValido.nome &&
                this.campoValido.email && Object.keys(this.select).length != 0) {
                    this.$validator.validateAll().then(result => {
                        if (result) 
                            this.enviarForm();
                    });   
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
