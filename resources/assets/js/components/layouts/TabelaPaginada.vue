<template>
    <div>
        <sara-tabela  :conteudotabela="dados" :header="header" @sort="sortBack" filtroinicial="nomeTela" 
            :issort="true" :select="true" :exceto="['idTelas','siglaTela']" :apagar="apagar" :editar="editar"></sara-tabela>
        <sara-pagination :souce="pagination" @navigate="buscaDados" :pages="pages"></sara-pagination> 
    </div> 
</template>
<script>
export default {
    props: ['link', 'header','busca', 'sort', 'apagar', 'editar'],
    data () {
        return {
            dados: {},
            pages: [],
            pagination: {},
            sortProperty: this.sort,
            sortDirection: 'asc'
        }
    },
    watch: {
        busca: function () {
            this.buscaDados();
        }
    },
    mounted() {
        this.buscaDados();
    },
    methods: {
        buscaDados(page = 1) {
            var url = this.busca === '' ? this.link+'/'+this.sortProperty+'/'+this.sortDirection+'?page='+page
                                        : this.link+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.busca+'?page='+page;
            
            axios.get(url).then (res => {
                this.dados = res.data.data;
                this.pagination = res.data;   
                this.pages = _.range(1,this.pagination.last_page + 1)
            }).catch(error => {
                console.log(error)
            });
        },
        sortBack(order) {
            this.sortProperty = order[0];
            this.sortDirection = order[1];
            this.buscaDados();
        }
    }
}
</script>

