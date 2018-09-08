<template>
   <div  v-if="empty">
        <vue-good-table 
            :mode="remote"
            :columns="columns"
            :rows="rows"
            @on-sort-change="sort"
            :sort-options="{ enabled: true }"
            styleClass="table table-hover">
            <span slot="table-row" slot-scope="props">
                <s-checkbox v-if="(props.column.field == 'check') && ckeck" 
                            :name="Object.values(props.formattedRow)[0] " :ischecked="getIndex(Object.values(props.formattedRow)[0]) > -1" 
                            :item="props.row" @checked="checked">
                </s-checkbox>
                <span v-if="(props.column.field == 'deletar') && apagar" class="btn-icon" v-tooltip.top-center="'Apagar'">
                    <s-formulario @submit="deletarItem"  :action="linkacoes + '/' + Object.values(props.formattedRow)[0]" 
                                            :token="token"  method="delete" :id="props.row.originalIndex">
                        <i  class="fas fa-trash-alt" @click="deletarItem(props.row.originalIndex, Object.values(props.formattedRow)[0])"></i>
                    </s-formulario>
                </span>
                <span v-if="(props.column.field == 'editar') && editar" class="btn-icon"  v-tooltip.top-center="'Editar'">
                        <a :href="linkacoes + '/' + Object.values(props.formattedRow)[0] +'/edit'"><i  class="fas fa-pen-alt"></i></a>
                </span>
                <span v-else>
                    {{props.formattedRow[props.column.field]}}
                </span>
            </span>
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
            <s-pagination @navigate="paginar" :pages="pagination.last_page " ref="paginacao"></s-pagination>
        </div>
   </div>
</template>
<script>
export default {
    props: {
        empty: {
            type: Boolean,
            required: true
        },
        pagination: {
            type: [Array, Object]
        },
        columns: {
            type: [Array, Object],
            required: true
        },
        rows: {
            type: [Array, Object],
            required: true
        },
        apagar: {
            type: Boolean,
            default: false
        },
        editar: {
            type: Boolean,
            default: false
        },
        linkacoes: {
            type: String
        },
        token: {
            type: String
        },
        sortProperty: {
            type: String
        },
        selecionados: {
            type: [Array, Object]
        },
        ckeck: {
            type: Boolean,
            default: false
        },
        sortDirection: {
            type: String
        },
        remoto: {
            type: Boolean,
            required: true
        }
    },
    data: function() {
        return {
            remote: this.remoto ? 'remote' : ''
        }
    },
    methods: {
        sort: function (params) {
            this.$emit('ordenar', params)
            this.resetPage();
        },
        deletarItem: function (index, id) {
            this.remoto ? document.getElementById(index).submit() : this.$emit('apagar', id)
        },
        paginar: function (params) {
             this.$emit('paginar', params)
        },
        checked: function(item) {
            this.$emit('checked', item)
        },
        getIndex(item) {
            return this.selecionados.map(e => Object.values(e)[0]).indexOf(item);
        },
        resetPage: function () {
            if(this.pagination.last_page > 1)
                this.$refs.paginacao.resetPage();
        }
    }
}
</script>

