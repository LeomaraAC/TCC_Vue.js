<template>
    <div>
        <s-formcard titulo="Remarcar Agendamento" icon="fas fa-calendar-alt" linkTabela="/atendimento/agendamento"
                @submitForm="validaForm" @resetForm="resetForm">
            <span slot="form">
                <s-formulario @submit="validaForm"  :action="'/atendimento/agendamento/remarcar/'+agendamento.idAgendamento" :token="token"  ref="form"
                                        method="put" id="remarcarAgendamento">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <s-input>
                                <input type="text" v-mask="'##/##/####'" 
                                    ref="campoData"
                                    v-model="data"
                                    masked
                                    autofocus                                   
                                    id="data"
                                    name="data"
                                    :class="{'form-control form-control-warning': true, 'is-invalid': erroData }"
                                    @blur="validaData"
                                    placeholder="Data"
                                />
                                <div slot="error">
                                    {{ erroData }}
                                </div>
                            </s-input>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <s-input>
                                <input type="text" v-mask="'##:##'" v-model="horaInicial" masked
                                    id="horaInicial" :class="{'form-control form-control-warning': true, 'is-invalid': erroHoraInicial }" 
                                    @blur="validaHoraInicial" name="horaInicial"
                                    placeholder="Horário inicial"/>
                                <div slot="error">
                                    {{ erroHoraInicial }}
                                </div>
                            </s-input>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <s-input>
                               <input type="text" v-mask="'##:##'" v-model="horaFinal"
                                    masked id="horaFinal" name="horaFinal"
                                    :class="{'form-control form-control-warning': true, 'is-invalid': erroHoraFim }" 
                                    @blur="validaHoraFinal"
                                    placeholder="Horário final"/>
                                <div slot="error">
                                    {{ erroHoraFim }}
                                </div>
                            </s-input>
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
            required: true,
            type: Object, Array
        },
        token: {
            required: true,
            type: String
        }
    },
    data: function (){
        return {
            data: this.agendamento.dataPrevisto,
            horaInicial: this.agendamento.horaPrevistaInicio,
            horaFinal: this.agendamento.horaPrevistaFim,
            erroData: "", 
            erroHoraInicial: "",
            erroHoraFim: '',
            msgSnack: '',
            showError: false
        }
    },
    methods: {
        validaForm: function () {
            this.validaHoraInicial();
            this.validaHoraFinal();
            this.validaData();
            console.log(this.agendamento.status == "Cancelada");
            
            if(this.data != this.agendamento.dataPrevisto || this.horaInicial != this.agendamento.horaPrevistaInicio
                    || this.horaFinal != this.agendamento.horaPrevistaFim || this.agendamento.status == "Cancelada"){
                if(this.erroHoraInicial == '' && this.erroData == '' && this.erroHoraFim == ''){
                    this.$refs.form.$el.submit();
                }
            } else {
                this.msgSnack = "Nenhum dado modificado.";
                this.showError = true;
                setTimeout( function () {this.showError = false}.bind(this), 5000);
            }
        },
        resetForm: function () {
            this.data = '';
            this.horaInicial = '';
            this.horaFinal = '';
            this.erroData = '';
            this.erroHoraInicial = '';
            this.erroHoraFim = '';
            this.$refs.campoData.focus();
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
        validaHoraInicial: function () {
            this.erroHoraInicial = this.horaIsValid(this.horaInicial, "horário inicial");
            if(this.horaFinal != '')
                this.validaHoraFinal();
        },
        validaHoraFinal: function () {
            let erro = this.horaIsValid(this.horaFinal, "horário final");
            let after = moment(this.horaFinal,'HH:mm', true).isAfter(moment(this.horaInicial,'HH:mm', true));
            
            if (erro == '' && after){
                this.erroHoraFim = '';
            }
            else if(erro != ''){
                this.erroHoraFim = erro;
            }
            else{
                this.erroHoraFim = 'O termino da reunião deve ser posterior ao seu início.'
            }
        },
        horaIsValid: function (hora, campo) {
             let horaSplit = hora.split(':');
            if(horaSplit.length == 1 && horaSplit[0].length == 0)
                        return "O campo "+campo+" é obrigatório.";
            else if (moment(hora, 'HH:mm', true).isValid()) {
                return "";
            } else
                return "O valor do campo "+campo+" é inválido.";
        }
    }
}
</script>

