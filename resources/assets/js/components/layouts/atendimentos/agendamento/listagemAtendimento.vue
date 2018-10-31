<template>
    <s-tabela :pagination="pagination" :columns="columns" :rows="rows" :remoto="true"
            :sortProperty="sortProperty" :sortDirection="sortDirection"
            :empty="empty"
            @ordenar="sort" @paginar="buscaDados" ref="tabela">
    </s-tabela>
</template>
<script>
export default {
    props: {
        linkfiltro: {
            required: true,
            type: String
        },
        columns: {
            required: true,
            type: Array
        },
    },
    data: function() {
        return{
            termo: '',
            responsavel: 'todos',
            sortProperty: "dataPrevisto",
            sortDirection: "asc",
            empty: false,
            pagination: {},
            rows: []
        }
    },
    mounted: function (){
        Event.listen('filtrarAgendamento', busca => {
            this.termo = busca[0];
            this.responsavel = busca[1];
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
            var url = this.termo === '' ? this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.responsavel+'?page='+page
                                        : this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.responsavel+'/'+this.termo+'?page='+page;
            axios.get(url).then (res => {
                this.empty = true;
                this.rows = res.data.data;
                this.pagination = res.data;
            });            
        },
        resetPage: function() {
            if(this.pagination.last_page > 1)
                this.$refs.paginacao.resetPage();
        }
    }
}
</script>

