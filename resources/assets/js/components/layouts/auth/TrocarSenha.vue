<template>
    <s-formcard titulo="Trocar Senha" icon="fas fa-key"
                @submitForm="validaForm" @resetForm="resetForm">
        <span slot="form">
            <s-formulario @submit="validaForm"  action="/user/trocar_senha" :token="token"  ref="form"
                                    method="POST" id="trocarSenha">
                <div class="row">
                    
                    <div class="col-md-4 col-sm-12">
                        <s-input id="senhaAtual"  type="password" placeholder="Senha atual"
                                ref="campoSenhaAtual" validate='required'
                                @validado="senhaAtualValida = $event"
                                :maxlength='10'/>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <s-input>
                            <input ref="campoSenha" id="senha"
                                        name="senha" v-model="senha"
                                        type="password"
                                        placeholder="Nova senha"  
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
                    <div class="col-md-4 col-sm-12">
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
        token: {
            type: String,
            required: true
        }
    },
    data: function () {
        return {
            senhaAtualValida: false,
            clickSubmit: false,
            senha: '',
            confSenha: ''
        }
    },
    mounted: function() {
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
        validaForm: function() {
            this.$refs.campoSenhaAtual.isValid();   
            this.clickSubmit = true;
        },
        resetForm: function() {
            this.$refs.campoDescricao.clear();
            this.$refs.campoDescricao.$validator.reset(); 
            this.$refs.campoDescricao.setFocus();    
        },
        isValid() {
            if(this.clickSubmit && this.senhaAtualValida) {
                this.$validator.validateAll().then(result => {
                    if (result) 
                        this.$refs.form.$el.submit();
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
