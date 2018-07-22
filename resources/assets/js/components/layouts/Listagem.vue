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
</sara-card>
<hr>
<sara-card :footer='pagination.last_page > 1'>
    <span slot="body">
        <sara-tabela  :conteudotabela="dados" :header="header" @sort="sort" :filtroinicial="sortProperty" 
        :select="false" :exceto="listaExceto" :issort="true" :apagar="apagar" :editar="editar"></sara-tabela>
    </span>
    <span slot="footer">
        <sara-pagination :souce="pagination" @navigate="buscaDados" :pages="pages"></sara-pagination>
    </span>
</sara-card>
</div>
</template>

<script>
    export default {
        props:[ 'linknovo', 'linkfiltro', 'titulo', "filtroinicial", 'header', 'exceto','apagar', 'editar'],
        data: function () {
            return {
                dados: {},
                pages: [],
                pagination: {},
                busca: '',
                sortProperty: this.filtroinicial,
                sortDirection: 'asc',
                listaExceto: this.exceto || []
            }
        },
        mounted() {
            this.buscaDados();
        },
        methods: {
            buscaDados(page = 1) {
                var url = this.busca === '' ? this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'?page='+page
                                          : this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.busca+'?page='+page;
                
                axios.get(url).then (res => {
                    this.dados = res.data.data;
                    this.pagination = res.data;   
                    this.pages = _.range(1,this.pagination.last_page + 1)
                });
            },
            sort(order) {
                this.sortProperty = order[0];
                this.sortDirection = order[1];
                this.buscaDados();
            },
            filtrar () {
                this.buscaDados();
            }
        }
    }
</script>
