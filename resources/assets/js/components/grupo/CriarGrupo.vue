<template>
    <s-card :titulo="titulo" :footer="true"  :icon="icon">
        <span slot="body">
            <div  class="text-left">
                <s-formulario @submit="validaForm"  :action="action" :token="token"  ref="form"
                                        :method="method" id="criarGrupo">
                    <input type="hidden"  name="idTelas" v-model="idTelas">
                    <div class="offset-md-2 col-md-8 col-sm-12">
                        <s-input>
                            <input ref="grupo" name="grupo" type="text" placeholder="Nome do grupo"  
                                    v-validate="'required|regex:^[a-zA-Z0-9\\- áÁéÉíÍóÓúÚçÇ`àÀãÃõÕôÔêÊ_]+$|min:3|max:60'"
                                    :class="{'form-control form-control-warning': true, 'is-invalid': errors.has('grupo') }"
                                    data-vv-validate-on="focusout|input" autofocus v-model="grupo" 
                            >
                            <span slot="error">
                                {{ errors.first('grupo') }}
                            </span>
                        </s-input>
                    </div>
                </s-formulario>
                <h5>Funcionalidades</h5>
                <hr>
                <button type="button"  class="btn btn-outline-success" @click="show"> <i class="fas fa-plus"></i>  Adicionar </button>
                <s-modal  @open="openModal" name="permissoes" title="Lista de Permissoes">
                    <span slot="modal-body">
                        <s-input  icon="fas fa-search">
                            <input name="busca" type="text" placeholder="Buscar...." class="form-control" autofocus 
                                v-model="busca" @keyup="buscaDados" >
                        </s-input>
                        <vue-good-table
                                mode="remote"
                                :columns="columns"
                                :rows="rows"
                                @on-sort-change="sort"
                                :sort-options="{ enabled: true }"
                                styleClass="table table-hover"
                            >
                            <span slot="table-row" slot-scope="props">
                                <s-checkbox v-if="props.column.field == 'idTelas'" 
                                    name="asd" :ischecked="getIndex(props.row.idTelas) > -1" :item="props.row" @checked="checked"></s-checkbox>
                                <span v-else>
                                {{props.formattedRow[props.column.field]}}
                                </span>
                            </span>
                            <div slot="emptystate">
                                <v-alert :value="true" color="red" icon="warning">
                                    Nenhum dado encontrado :(
                                </v-alert>
                            </div>
                        </vue-good-table>
                        <s-pagination :souce="pagination" @navigate="buscaDados" :pages="pages"></s-pagination>
                    </span>
                    <span slot="modal-footer">
                        <button type="button" class="btn btn-outline-primary" @click="closeModal">Fechar</button>
                    </span>
                </s-modal>
                <div v-if="this.dadosSelect.length > 0">
                    <vue-good-table
                            :columns="columns"
                            :rows="dadosSelect"
                            :sort-options="{ enabled: true }"
                            styleClass="table table-hover"
                    >
                        <span slot="table-row" slot-scope="props">
                            <span v-if="props.column.field == 'idTelas'" class="btn-icon" @click="removeItem(props.row)">
                                <i  class="fas fa-trash-alt"></i>
                            </span>
                            <span v-else>
                            {{props.formattedRow[props.column.field]}}
                            </span>
                        </span>
                    </vue-good-table>
                </div>
            </div>
        </span>
        <span slot="footer">
            <button  type="button" @click.prevent="validaForm" class="btn btn-outline-success"> 
                <i class="fas fa-save"></i> Salvar 
            </button>
            <button type="button" @click.prevent="reset" class="btn btn-outline-secondary">
                <i class="fas fa-eraser"></i> Limpar
            </button>
        </span>
    </s-card>
</template>

<script>
export default {
  props: {
    titulo: {
        required: true,
        type: String
    },
    nomegrupo: {
        type: String
    },
    dadosselecionados: {
        type: Array
    },
    method: {
        required: true,
        type: String
    },
    action: {
        required: true,
        type: String
    },
    token: {
        required: true,
        type: String
    },
    icon: {
        type: String
    },
    idgrupo: {
        type: String
    }
  },
  data: function() {
    return {
      dadosSelect: [],
      idTelas: [],
      columns: [],
      busca: "",
      rows: [],
      sortProperty: "",
      sortDirection: "asc",
      pagination: [],
      pages: [],
      grupo: this.nomegrupo || ''
    };
  },
  mounted: function() {
      console.log(this.nomegrupo)
    if (this.dadosselecionados) 
      this.idTelas = this.dadosselecionados.map(e => e.idTelas);
  },
  methods: {
    validaForm: function(event) {
      this.$validator.validateAll().then(result => {
        if (result) 
            this.$refs.form.$el.submit(); // Acessa o form que está em outro componente e faz o submit
      });
    },
    reset: function () {
        this.dadosSelect = this.idTelas = []; // Limpa os dados selecionados
        this.grupo = ''; // Limpa o nome do grupo
        this.$validator.reset(); // Reseta os erros
        this.$refs.grupo.focus(); // Focus no campo 'grupo'
    },
    show() {
      this.$modal.show("permissoes");
    },
    closeModal() {
      this.$modal.hide("permissoes");
    },
    openModal() {
      this.columns = [
        { field: "idTelas", label: "", width: "50px" },
        { field: "nomeTela", label: "Permissões" },
        { field: "siglaTela", label: "Sigla", hidden: true }
      ];
      this.sortProperty = "nomeTela";
      this.buscaDados();
    },
    sort(params) {
      this.sortDirection = params.sortType;
      this.sortProperty = this.columns[params.columnIndex].field;
      this.buscaDados();
    },
    buscaDados(page = 1) {
      var url = this.busca === "" ? this.link + "/" + this.sortProperty + "/" + this.sortDirection + "?page=" + page
                                : this.link + "/" + this.sortProperty + "/" + this.sortDirection + "/" + this.busca + "?page=" + page;
      axios.get(url).then(res => {
        this.rows = res.data.data;
        this.pagination = res.data;
        this.pages = _.range(1, this.pagination.last_page + 1);
      });
    },
    filtrar() {
      this.buscaDados();
    },
    checked(params) {
      if (params[0]) {
        var item = this.novoItem(params[1]);
        this.dadosSelect.push(item);
      } else {
        var index = this.getIndex(params[1].idTelas);
        if (index > -1) this.dadosSelect.splice(index, 1);
      }

      this.idTelas = this.dadosSelect.map(e => e.idTelas);
    },
    novoItem(params) {
      var item = {
        idTelas: params.idTelas,
        nomeTela: params.nomeTela,
        siglaTela: params.siglaTela
      };
      return item;
    },
    getIndex(item) {
      return this.dadosSelect.map(e => e.idTelas).indexOf(item);
    },
    removeItem(params) {
      var index = this.getIndex(params.idTelas);
      if (index > -1) this.dadosSelect.splice(index, 1);
      this.idTelas = this.dadosSelect.map(e => e.idTelas);
    }
  }
};
</script>
