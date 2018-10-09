<template>
    <s-formcard titulo="Cadastro de Alunos" icon="fas fa-user-graduate" @submitForm="validaForm" @resetForm="resetForm">
        <span slot="form">
            <s-formulario @submit="validaForm"  :action="action" :token="token"  ref="form"
                                    :method="method" id="criarAluno">
                <div class="row">
                    <div class="col-lg-2  col-md-3 col-sm-12">
                        <s-input id="prontuario"  type="text" placeholder="Prontuário" 
                            ref="campoProntuario" :valor="valores.prontuario"
                            validate='required|alpha_num|min:5|max:10' :maxlength='10'
                            :autofocus="true" @validado="campoValido.prontuario = $event" />
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12">
                        <s-input id="nome"  type="text" placeholder="Nome" :valor="valores.nome"
                            ref="campoNome" validate='required|alpha_spaces|min:3|max:60'
                            @validado="campoValido.nome = $event" :maxlength='60'/>
                    </div>
                    <div class="col-lg-5 col-md-4 col-sm-12">
                        <s-input id="email"  type="email" placeholder="Email" 
                            ref="campoEmail" validate='required|email|max:60' :maxlength='60'
                            :valor="valores.email" @validado="campoValido.email = $event"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-12 col-sm-12">
                        <s-input id="telefone"  type="text" placeholder="Telefone" 
                            ref="campoTelefone" validate='required|min:14'
                            :maxlength='20' v-mask="['(##) ####-####', '(##) #####-####']"
                            :valor="valores.telefone" @validado="campoValido.telefone = $event" />
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12">
                        <s-select ref="selectCurso"
                            id="curso"
                            :options="cursos"
                            placeholder="Curso"
                            track-by="idCurso"
                            label="descricao"
                            campo="Curso"
                            :required="true"
                            :selected = valores.curso
                            @selected="selectCurso = $event"/>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <s-select ref="selectModulo"
                            id="semestre"
                            :options="semestre"
                            placeholder="Módulo\Ano"
                            campo="Módulo\Ano"
                            :required="true"
                            :selected = valores.semestre
                            @selected="selectModulo = $event"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea class="form-control" id="observacao" name="observacao" rows="3" 
                          placeholder="Observações" v-model="valores.observacao" />
                    </div>
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
        valores: {
            type: Object,
            default: function () {
                return {
                    nome: '',
                    prontuario: '',
                    email: '',
                    telefone: '',
                    semestre: '',
                    observacao: '',
                    curso: '',
                }
            }
        }
    },
    mounted: function () {
        axios.get('/cursos').then (res => {
            this.cursos = res.data;
        })
    },
    data: function () {
        return { 
            selectCurso: {},
            selectModulo: {},
            campoValido: {
                prontuario: false,
                nome: false,
                email: false,
                telefone: false,
            },
            clickSubmit: false,
            semestre: [],
            cursos: []
        }
    },
    methods: {
        validaForm: function() {
            this.$refs.selectModulo.onTouch();   
            this.$refs.selectCurso.onTouch();   

            this.$refs.campoProntuario.isValid();   
            this.$refs.campoNome.isValid();   
            this.$refs.campoEmail.isValid(); 
            this.$refs.campoTelefone.isValid(); 
            this.clickSubmit = true;
        },
        resetForm: function() {
            //Prontuário
            this.$refs.campoProntuario.clear();
            this.$refs.campoProntuario.$validator.reset(); 
            //Nome
            this.$refs.campoNome.clear();
            this.$refs.campoNome.$validator.reset();
            //Email
            this.$refs.campoEmail.clear();
            this.$refs.campoEmail.$validator.reset(); 
            //Telefone
            this.$refs.campoTelefone.clear();
            this.$refs.campoTelefone.$validator.reset(); 
            
            //Curso
            this.$refs.selectCurso.reset();

            //Módulo
            this.$refs.selectModulo.reset();

            //Focus no campo Prontuário
            this.$refs.campoProntuario.setFocus();    
        },
        isValid() {
            if(this.clickSubmit && this.campoValido.prontuario && this.campoValido.nome &&
                this.campoValido.email &&this.campoValido.telefone
                && Object.keys(this.selectCurso).length != 0 && 
                Object.keys(this.selectModulo).length != 0) {
                    this.$refs.form.$el.submit();
            }
                     
            this.clickSubmit = false;
        }
    },
    watch: {
        clickSubmit: function() {
            if(this.clickSubmit)
                this.isValid()
        },
        selectCurso: function () {
            this.semestre = [];
            var cont;
            for(cont = 1; cont <= this.selectCurso.duracao; cont++) {
                this.semestre.push(cont);
            }
            
        }
    }
}
</script>
