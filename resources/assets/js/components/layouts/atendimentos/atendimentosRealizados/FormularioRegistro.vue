<template>
   <div>
        <s-formcard titulo="Registrar Atendimento" icon="fas fa-headset" linkTabela="/atendimento/agendamento"
                @submitForm="validaForm" @resetForm="resetForm">
            <span slot="form">
                <s-formulario @submit="validaForm"  action="#" :token="token"  ref="form"
                                method="POST" id="registrarAgendamento">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6">
                                        <s-input>
                                            <input v-mask="'##/##/####'" 
                                                ref="campoData"
                                                masked
                                                v-model="data"
                                                id="data"
                                                :class="{'form-control form-control-warning': true, 'is-invalid': erroData }"
                                                @blur="validaData"
                                                placeholder="Data"
                                                :autofocus="true"
                                                />
                                            <div slot="error">
                                                {{erroData}}
                                            </div>
                                        </s-input>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <s-input>
                                            <input v-mask="'##:##'" 
                                                ref="campoHora"
                                                masked
                                                v-model="hora"
                                                id="hora"
                                                @blur="validaHora"
                                                :class="{'form-control form-control-warning': true, 'is-invalid': erroHora }"
                                                placeholder="Hora"
                                                />
                                            <div slot="error">
                                                {{erroHora}}
                                            </div>
                                        </s-input>
                                    </div>
                                    <div class="col-md-3 col-sm-6" v-if="agendamento.formaAtendimento == 'Familiar'">
                                        <s-checkbox name="familiar" :ischecked="familiaCompareceu" ref="campoFamilia"
                                                    label="A família compareceu?" @checked="checkedFamilia">
                                        </s-checkbox>
                                    </div>
                                    <div class="col-md-3 col-sm-6" v-if="familiaCompareceu">
                                        <s-input>
                                                <input  ref="campoParentesco" id="parentesco"
                                                        name="parentesco" v-model="parentesco"
                                                        type="text"
                                                        placeholder="Grau de Parentesco"  
                                                        v-validate="'required|alpha_spaces|min:2|max:60'"
                                                        :class="{'form-control form-control-warning': true, 'is-invalid': errors.has('parentesco') }"
                                                        data-vv-validate-on="focusout"
                                                        maxlength="60"
                                                >
                                                <div slot="error">
                                                    {{ errors.first('parentesco') }}
                                                </div>
                                        </s-input>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" id="comentarios" placeholder="Resumo" rows="2" v-model="comentario"></textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <s-select ref="selectResponsavel"
                                            id="responsavel"
                                            :options="usuarios"
                                            placeholder="Responsável"
                                            track-by="idUser"
                                            label="nome"
                                            campo="responsável"
                                            :messageErro="erroResponsavel"
                                            :erro="erroResponsavelRepetido"
                                            :selected = userSelecionar
                                            @selected="setSelectResponsavel"
                                            >
                                        </s-select>
                                    </div>
                                    <div class="col-sm-2" v-if="temResponsavel != ''">
                                        <button type="button" id="adicionarComentario" @click="adicionar" class="btn btn-outline-success mb-3" v-tooltip.top-center="'Adicionar'">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <div class="row" v-if="lista.length > 0">
                                    <div class="col-sm-12">
                                        <s-tabela   :columns="columns" :rows="lista" :empty="true"
                                                    :remoto="false"  :apagar="true" @apagar="remover">
                                        </s-tabela>
                                    </div>
                                </div>
                </s-formulario>
            </span>
        </s-formcard>
        <s-snackbar cor="red" :msg="msgSnack" :show="showError"></s-snackbar>
   </div>
</template>
<script>
var moment = require('moment');
export default {
    props: {
        agendamento: {
            type: [Object, Array],
            required: true
        },
        usuarios: {
            type: [Object, Array],
            required: true
        },
        usuarioAtual: {
            type: [Object, Array],
            required: true
        },
        token: {
            required: true,
            type: String
        }
    },
    mounted: function() {
        if(this.agendamento.responsavel == 'Particular')
            this.userSelecionar = this.usuarioAtual.idUser;
    },
    data: function () {
        return {
            familiaCompareceu: false,
            parentesco: '',
            /** LISTA */
            comentario: '',
            temResponsavel: false,
            reponsavel: [],
            erroResponsavelRepetido: false,
            erroResponsavel: '',
            lista: [],
            columns: [
                { field: "idUser", label: "",  hidden: true },
                { field: "deletar", label: '', width: '50px', sortable: false}, 
                { field: "responsavel", label: "Responsável" }
            ],
            /** DATA */
            erroData: '',
            data:'',
            /** HORA */
            erroHora: '',
            hora: '',

            /** SNACKBAR */
            msgSnack: '',
            showError: false
        }
    },
    methods: {
        validaForm: function () {
            this.validaData();
            this.validaHora();
            const temLista = this.lista.length > 0;
            if(!temLista) {
                this.openSnackbar("É obrigatório informar o responsável pelo atendimento!");
            } 
            else if(this.comentario == '') {
                this.openSnackbar("É obrigatório informar o resumo do atendimento!");
            }
            else  if(this.erroData == '' && this.erroHora == ''){
                this.$validator.validateAll().then(result => {
                        if (result) {
                            axios.post('/atendimento/agendamento/registrar', {
                                idAgendamento: this.agendamento.idAgendamento,
                                data: this.data,
                                hora: this.hora,
                                familia: this.familiaCompareceu,
                                parentesco: this.parentesco,
                                responsaveis: this.lista,
                                resumo: this.comentario
                            })
                            .then((response) => {
                                window.location.href = '/atendimento/realizados'
                            })
                            .catch((error) => {  
                                // console.log(error.response.data);
                                let listOfObjects = Object.keys(error.response.data).map((key) => {
                                    return error.response.data[key]
                                })
                                this.openSnackbar(listOfObjects[1]);
                            })
                        }
                            
                    });  
            }
            
        },
        resetForm: function () {},
        openSnackbar: function (message) {
            this.msgSnack = message;
            this.showError = true;
            setTimeout( function () {this.showError = false}.bind(this), 5000);
        },
        checkedFamilia: function (value) {
            this.familiaCompareceu = value[0];
        },
        setSelectResponsavel: function (value) {             
            let existe = this.getIndex(value.idUser);          
            if(value.length != 0) {
                if(existe < 0) {
                    this.reponsavel = value;
                    this.temResponsavel = true;
                    this.erroResponsavelRepetido = false;
                    this.erroResponsavel = '';
                } else {
                    this.erroResponsavel = value.nome + " já está selecionado!"
                    this.erroResponsavelRepetido = true;
                }
            }
            else {
                this.erroResponsavelRepetido = false;
                this.erroResponsavel = '';
                this.temResponsavel = false;
            }
        },
        adicionar: function () {
            var item = {
                idUser: this.reponsavel.idUser,
                responsavel: this.reponsavel.nome
            }             
            this.lista.push(item);         
            this.$refs.selectResponsavel.reset();
        },
        getIndex(id) {
            return this.lista.map(e => e.idUser).indexOf(id);
        },
        remover: function (item) {
            var index = this.getIndex(item);
            this.lista.splice(index, 1);
        },
        validaData: function () {
            let dataSplit = this.data.split('/');
            if(dataSplit.length == 1 && dataSplit[0].length == 0)
                this.erroData = "O campo data é obrigatório.";
            else if(dataSplit[2] < 2000)
                this.erroData = "A data deve ser superior ou igual ao ano 2000";
            else if(dataSplit[2] > 2200)
                this.erroData = "A data deve ser inferior ou igual ao ano 2200";
            else if (moment(this.data, 'DD/MM/YYYY',true).isValid()) {
                this.erroData = ""
            } else
                this.erroData = "O valor do campo data é inválido.";
        },
        validaHora: function () {
            let horaSplit = this.hora.split(':');            
            if(horaSplit.length == 1 && horaSplit[0].length == 0){
                this.erroHora = "O campo hora é obrigatório.";
            }
            else if (moment(this.hora, 'HH:mm', true).isValid()) {
                this.erroHora = "";
            } else
                this.erroHora = "O valor do campo hora é inválido.";
        }
    }
}
</script>
