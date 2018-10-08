<template>
<div class="text-left">
    <s-card :titulo="titulo" :footer="adicionar" :icon="icon">
        <span slot="body">
            <div class="offset-md-2 col-md-8 col-sm-12">
                <s-input  icon="fas fa-search">
                    <input name="busca" type="text" placeholder="Buscar...." class="form-control" autofocus 
                    v-model="busca" @keyup.enter="filtrar">
                </s-input>
            </div>
        </span>
        <span slot="footer" v-if="adicionar">
            <div class="text-left">
                <a id="novo" :href="linknovo" class="btn btn-outline-info"><i class="fas fa-plus"></i> Novo</a>
            </div>
        </span>
    </s-card>
    <hr>
    <s-card :footer='false'>
        <span slot="body">
            <s-tabela :pagination="pagination" :columns="columns" :rows="rows" :remoto="true"
                    :sortProperty="sortProperty" :sortDirection="sortDirection" :apagar="apagar" :editar="editar" :token="token"
                    :linkacoes="linkacoes" :empty="empty"
                    @ordenar="sort" @paginar="buscaDados" ref="tabela">
            </s-tabela>
        </span>
    </s-card>
</div>
</template>

<script>
    export default {
        props: {
            linknovo: {
                required: true,
                type: String
            },
            adicionar: {
                required: true,
                type: Boolean
            },
            linkfiltro: {
                required: true,
                type: String
            },
            titulo: {
                required: true,
                type: String
            },
            filtroinicial: {
                required: true,
                type: String
            },
            columns: {
                required: true,
                type: Array
            },
            apagar: {
                type: Boolean,
                default: false
            },
            editar: {
                type: Boolean,
                default: false
            },
            token: {
                type: String
            },
            icon: {
                type: String
            },
            linkacoes: {
                type: String
            }
        },
        data: function () {
            return {
                pagination: {},
                busca: '',
                sortProperty: this.filtroinicial,
                sortDirection: 'asc',
                rows: [],
                empty: false
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
                    this.empty = true;
                    this.rows = res.data.data;
                    this.pagination = res.data;
                });
            },
            sort(params) {
                this.sortDirection = params.sortType == 'asc' ? 'desc' : 'asc';
                this.sortProperty = this.columns[params.columnIndex].field;
                this.$refs.tabela.resetPage();
                this.buscaDados();
            },
            filtrar () {
                this.buscaDados();
                this.$refs.tabela.resetPage();
            }
        }
    }
</script>

