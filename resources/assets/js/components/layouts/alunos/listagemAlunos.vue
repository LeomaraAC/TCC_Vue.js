<template>
   <div  v-if="not_empty">
        <vue-good-table 
            mode="remote"
            :columns="columns"
            :rows="rows"
            @on-sort-change="sort"
            :sort-options="{ enabled: true }"
            styleClass="table table-hover">
            <!-- SLOT DAS LINHAS -->
            <span slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'pdf' && permissao_visualizar" class="btn-icon"  v-tooltip.top-center="'Visualizar'">
                        <a :href="linkacoes + '/' + Object.values(props.formattedRow)[1]"><i  class="fas fa-file-pdf"></i></a>
                </span>
                <span v-else>
                    {{props.formattedRow[props.column.field]}}
                </span>
            </span>
            <!-- SLOT DAS COLUNAS -->
            <span slot="table-column" slot-scope="props">
                <span v-if="props.column.label !=''" class="hover">
                    <i v-if="sortProperty == props.column.field" :class="{'fas fa-sort-alpha-down': sortDirection == 'asc' , 
                                        'fas fa-sort-alpha-up': sortDirection == 'desc'}"></i> {{props.column.label}}
                </span>
                <span v-else>
                    {{props.column.label}}
                </span>
            </span>
            <div slot="emptystate">
                <v-alert :value="true" color="red" icon="warning">
                    Nenhum dado encontrado :(
                </v-alert>
            </div>
        </vue-good-table>
        <div v-if="pagination && pagination.last_page > 1"  class="text-left">
            <s-pagination @navigate="buscaDados" :pages="pagination.last_page " ref="paginacao"></s-pagination>
        </div>
   </div>
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
            this.resetPage();
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
        },
        deletarItem: function (index) {
            document.getElementById(index).submit()
        },
        resetPage: function() {
            if(this.pagination.last_page > 1)
                this.$refs.paginacao.resetPage();
        }
    },
    mounted: function () {
        this.buscaDados();
        Event.listen('filtrar', termoBusca => {
            this.buscar = termoBusca;
            this.resetPage();
            this.buscaDados();
        });
    }
}
</script>

