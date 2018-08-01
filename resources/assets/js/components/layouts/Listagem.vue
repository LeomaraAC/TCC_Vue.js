<template>
<div class="text-left">
    <s-card :titulo="titulo" :footer="true" :icon="icon">
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
            <span slot="table-row" slot-scope="props">
                <span v-if="(props.column.field == 'deletar') && apagar" class="btn-icon">
                    <s-formulario @submit="executaForm"  :action="linkacoes + Object.values(props.formattedRow)[0]" 
                                            :token="token"  method="delete" :id="props.row.originalIndex">
                        <i  class="fas fa-trash-alt" @click="executaForm(props.row.originalIndex)"></i>
                    </s-formulario>
                </span>
                <span v-if="(props.column.field == 'editar') && editar" class="btn-icon">
                        <a :href="linkacoes + Object.values(props.formattedRow)[0] +'/edit'"><i  class="fas fa-pen-alt"></i></a>
                </span>
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
    </span>
    <span slot="footer">
        <s-pagination :souce="pagination" @navigate="buscaDados" :pages="pages"></s-pagination>
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
            linkfiltro: {
                required: true,
                type: String
            },
            titulo: {
                required: true,
                type: String
            },
            filtroinicial: {
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
                pages: [],
                pagination: {},
                busca: '',
                sortProperty: this.filtroinicial,
                sortDirection: 'asc',
                listaExceto: this.exceto || [],
                rows: [],
            }
        },
        mounted() {
            this.buscaDados();
        },
        methods: {
            executaForm: function(index){
                document.getElementById(index).submit();
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

