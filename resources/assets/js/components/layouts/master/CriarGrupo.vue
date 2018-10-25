<template>
    <s-formcard :titulo="titulo" :icon="icon" @submitForm="validaForm" @resetForm="resetForm">
        <span slot="form">
            <s-formulario @submit="validaForm"  :action="action" :token="token"  ref="form"
                                    :method="method" id="criarGrupo">
                <input type="hidden"  name="idTelas" v-model="idTelas">
                <div class="offset-md-3 col-md-6 col-sm-12">
                    <s-input>
                        <input ref="grupo" name="grupo" type="text" placeholder="Nome do grupo"  
                                v-validate="'required|regex:^[a-zA-Z0-9\\- áÁéÉíÍóÓúÚçÇ`àÀãÃõÕôÔêÊ_]+$|min:3|max:60'"
                                :class="{'form-control form-control-warning': true, 'is-invalid': errors.has('grupo') }"
                                data-vv-validate-on="focusout" autofocus v-model="grupo" maxlength="60"
                        >
                        <span slot="error">
                            {{ errors.first('grupo') }}
                        </span>
                    </s-input>
                </div>
            </s-formulario>
            <h5>Funcionalidades</h5>
            <hr>
            <button type="button"  class="btn btn-outline-success mb-3" @click="show"> <i class="fas fa-plus"></i>  Adicionar </button>
            <s-modal  @open="openModal" name="permissoes" title="Lista de Permissoes">
                <span slot="modal-body">
                    <s-input  icon="fas fa-search">
                        <input name="busca" type="text" placeholder="Buscar...." class="form-control" autofocus 
                            v-model="busca" @keyup.enter="buscaDados" >
                    </s-input>
                    <s-tabela :pagination="pagination" :columns="columns" :rows="rows" :remoto="true"
                            :sortProperty="sortProperty" :selecionados="dadosSelect" :ckeck="true" :sortDirection="sortDirection"
                            @ordenar="sort" @paginar="buscaDados" @checked="checked" :empty="empty">
                    </s-tabela>
                </span>
                <span slot="modal-footer">
                    <button type="button" class="btn btn-outline-primary" @click="closeModal">Fechar</button>
                </span>
            </s-modal>
            <div v-if="dadosSelect.length > 0">
                <s-tabela :columns="columnsSelect" :rows="dadosPaginacao" :empty="true"
                                        :remoto="false"  :apagar="true" @apagar="removeItem">
                </s-tabela>
                <s-pagination v-if="dadosSelect.length > size" @navigate="paginar" :pages="pageCount()" ref="paginacao"></s-pagination>
            </div>
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
    nomegrupo: {
        type: String
    },
    dadosselecionados: {
        type: [String, Array]
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
      columnsSelect: [],
      busca: "",
      rows: [],
      sortProperty: "",
      sortDirection: "asc",
      pagination: [],
      grupo: this.nomegrupo || '',
      link: 'http://projetosara.meu/master/permissoes',
      empty: false,
      size: 5,
      pageAtual: 1,
      dadosPaginacao: []
    };
  },
  mounted: function() {
    if (this.dadosselecionados) {
        if (typeof this.dadosselecionados === "string")  {
            const dados = this.dadosselecionados.split(",");
            const url = 'http://projetosara.meu/master/find_ids';
            axios.get(url, {
                params: {
                    ids: dados
                }
            }).then(res => {
                const dados = res.data
                this.dadosSelect = dados;
                dados.forEach(item => this.idTelas.push(item.idTelas));
                console.log(this.idTelas);
                
            });
        }else {
            this.dadosselecionados.forEach( param => {
                var item = this.novoItem(param);
                this.dadosSelect.push(item);
                this.idTelas.push(item.idTelas);
            });
        }
        this.paginar();

    }

    this.columns = [
        { field: "idTelas", label: "",  hidden: true },
        { field: "check", label: '', width: '50px', sortable: false}, 
        { field: "descricao", label: "Permissões" },
        { field: "nome", label: "Nome", hidden: true }
    ];
    this.columnsSelect = [
        { field: "idTelas", label: "",  hidden: true },
        { field: "deletar", label: '', width: '50px', sortable: false}, 
        { field: "descricao", label: "Permissões" },
        { field: "nome", label: "Nome", hidden: true }
    ];
  },
  methods: {
    validaForm: function(event) {
      this.$validator.validateAll().then(result => {
        if (result) 
            this.$refs.form.$el.submit(); // Acessa o form que está em outro componente e faz o submit
      });
    },
    resetForm: function () {
        this.dadosSelect = this.idTelas = this.dadosPaginacao = []; // Limpa os dados selecionados
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
      this.sortProperty = "descricao";
      this.buscaDados();
    },
    sort(params) {
      this.sortDirection = params.sortType == 'asc' ? 'desc' : 'asc';
      this.sortProperty = this.columns[params.columnIndex].field;
      this.buscaDados();
    },
    buscaDados(page = 1) {
      var url = this.busca === "" ? this.link + "/" + this.sortProperty + "/" + this.sortDirection + "?page=" + page
                                : this.link + "/" + this.sortProperty + "/" + this.sortDirection + "/" + this.busca + "?page=" + page;
      axios.get(url).then(res => {
         this.empty = true;
        this.rows = res.data.data;
        this.pagination = res.data;
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
        if (index > -1) 
            this.dadosSelect.splice(index, 1);
      }

      this.idTelas = this.dadosSelect.map(e => e.idTelas);
      this.paginar(this.pageAtual);
    },
    novoItem(params) {
      var item = {
        idTelas: params.idTelas,
        descricao: params.descricao,
        nome: params.nome
      };
      return item;
    },
    getIndex(item) {
      return this.dadosSelect.map(e => e.idTelas).indexOf(item);
    },
    removeItem(id) {
      var index = this.getIndex(id);
      if (index > -1) this.dadosSelect.splice(index, 1);
      this.idTelas = this.dadosSelect.map(e => e.idTelas);
      this.paginar(this.pageAtual);
    },
    pageCount(){
        let l = this.dadosSelect.length;
        let s = this.size;
        return Math.ceil(l/s);
    },
    paginar(page = 1){
        if(page > this.pageCount()){
            page--;            
            this.$refs.paginacao.setPage(page);
        }
        this.pageAtual = page;
        const start = (page - 1) * this.size;
        const end = start + this.size;
        this.dadosPaginacao = this.dadosSelect.slice(start, end); 
    }
  }
};
</script>
