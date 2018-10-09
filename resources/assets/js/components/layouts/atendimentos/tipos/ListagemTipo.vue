<template>
    <s-tabela :pagination="pagination" :columns="columns" :rows="rows" :remoto="true"
                    :sortProperty="sortProperty" :sortDirection="sortDirection"
                    :apagar="apagar" :editar="editar" :token="token"
                    :linkacoes="linkacoes" :empty="empty"
                    @ordenar="sort" @paginar="buscaDados" ref="tabela">
            </s-tabela>
</template>
<script>
export default {
    props: {
        apagar: {
            type: Boolean,
            default: false
        },
        columns: {
            required: true,
            type: Array
        },
        editar: {
            type: Boolean,
            default: false
        },
        linkacoes: {
            type: String
        },
        linkfiltro: {
            required: true,
            type: String
        },
        token: {
            type: String
        },
    },
    data: function () {
        return {
            pagination: {},
            rows: [],
            sortProperty: "descricao",
            sortDirection: "asc",
            empty: false,
            buscar: ''
        }
    },
    mounted() {
            Event.listen('filtrar', termoBusca => {
                this.buscar = termoBusca;
                this.resetPage();
                this.buscaDados();
            });
            this.buscaDados();
        },
        methods: {
            sort(params) {
                this.sortDirection = params.sortType == 'asc' ? 'desc' : 'asc';
                this.sortProperty = this.columns[params.columnIndex].field;
                this.resetPage();
                this.buscaDados();
            },

            buscaDados: function (page = 1) {
                var url = this.buscar === '' ? this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'?page='+page
                                            : this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.buscar+'?page='+page;
                
                axios.get(url).then (res => {
                    this.empty = true;
                    this.rows = res.data.data;
                    this.pagination = res.data;
                });            
            },
            deletarItem: function (index) {
                document.getElementById(index).submit()
            },
            resetPage: function() {
                if(this.pagination.last_page > 1)
                    this.$refs.paginacao.resetPage();
            }
        }
}
</script>

