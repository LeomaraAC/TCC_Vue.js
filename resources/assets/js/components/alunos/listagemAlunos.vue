<template>
    <s-tabela :pagination="pagination" :columns="columns" :rows="rows" :remoto="true"
                    :sortProperty="sortProperty" :sortDirection="sortDirection" 
                    :editar="permissao_editar" :token="token" :apagar="permissao_apagar" 
                    :linkacoes="linkacoes" :empty="not_empty"
                    @ordenar="sort" @paginar="buscaDados" ref="tabela">
    </s-tabela>
</template>
<script>
export default {
    props: {
        linkacoes: {
            required: true,
            type: String
        },
        linkfiltro: {
            required: true,
            type: String
        },
        columns: {
            required: true,
            type: Array
        },
        permissao_visualizar: {
            type: Boolean,
            default: false
        },
    },
    data: function () {
        return {
            pagination: {},
            buscar: '',
            sortProperty: 'nome',
            sortDirection: 'asc',
            rows: [],
            not_empty: false
        }
    },
    methods: {
        sort(params) {
            this.sortDirection = params.sortType == 'asc' ? 'desc' : 'asc';
            this.sortProperty = this.columns[params.columnIndex].field;
            this.$refs.tabela.resetPage();
            this.buscaDados();
        },

        buscaDados: function (page = 1) {
            var url = this.buscar === '' ? this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'?page='+page
                                        : this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.buscar+'?page='+page;
            
            axios.get(url).then (res => {
                this.not_empty = true;
                this.rows = res.data.data;
                this.pagination = res.data;
            });            
        }
    },
    mounted: function () {
        this.buscaDados();
        Event.listen('filtrar', termoBusca => {
            this.buscar = termoBusca;
            this.$refs.tabela.resetPage();
            this.buscaDados();
        });
    }
}
</script>

