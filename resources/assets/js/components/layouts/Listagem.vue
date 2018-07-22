<template>
<div class="text-left">
    <s-card :titulo="titulo" :footer="true">
    <span slot="body">
        <div class="offset-md-2 col-md-8 col-sm-12">
            <s-input  icon="fas fa-search">
                <input name="busca" type="text" placeholder="Buscar...." class="form-control" autofocus 
                v-model="busca" @keyup="filtrar">
            </s-input>
        </div>
    </span>
    <span slot="footer">
        <div class="text-left">
            <a :href="linknovo" class="btn btn-outline-info"><i class="fas fa-plus"></i> Novo</a>
        </div>
    </span>
</s-card>
<hr>
<s-card :footer='pagination.last_page > 1'>
    <span slot="body">
        <vue-good-table
            mode="remote"
            :columns="columns"
            :rows="rows"
            @on-sort-change="sort"
            :sort-options="{ enabled: true }"
            styleClass="table table-hover">
      </vue-good-table>
    </span>
    <span slot="footer">
        <s-pagination :souce="pagination" @navigate="buscaDados" :pages="pages"></s-pagination>
    </span>
</s-card>
</div>
</template>

<script>
    export default {
        props:[ 'linknovo', 'linkfiltro', 'titulo', "filtroinicial", 'columns','apagar', 'editar'],
        data: function () {
            return {
                pages: [],
                pagination: {},
                busca: '',
                sortProperty: this.filtroinicial,
                sortDirection: 'asc',
                listaExceto: this.exceto || [],
                rows: [ ],
            }
        },
        mounted() {
            this.buscaDados();
        },
        methods: {
            selectionChanged(v) {
                console.log(v);
            },
            buscaDados(page = 1) {
                var url = this.busca === '' ? this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'?page='+page
                                          : this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.busca+'?page='+page;
                
                axios.get(url).then (res => {
                    this.rows = res.data.data;
                    this.pagination = res.data;   
                    this.pages = _.range(1,this.pagination.last_page + 1)
                });
            },
            sort(params) {
                this.sortDirection = params.sortType;
                this.sortProperty = this.columns[params.columnIndex].field;
                this.buscaDados();
            },
            filtrar () {
                this.buscaDados();
            }
        }
    }
</script>

