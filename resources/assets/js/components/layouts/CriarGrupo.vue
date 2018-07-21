<template>
    <sara-card titulo="Criar Grupo" :footer="false">
    <span slot="body">
        <div  class="text-left">
            <div class="offset-md-2 col-md-8 col-sm-12">
                <sara-input>
                    <input name="grupo" type="text" placeholder="Nome do grupo"  
                                v-validate="'required|alpha_spaces|min:3|max:60'"
                            :class="{'form-control form-control-warning': true, 'is-invalid': errors.has('grupo') }"
                            data-vv-validate-on="focusout|input" autofocus >
                    <span slot="error">
                        {{ errors.first('grupo') }}
                    </span>
                </sara-input>
            </div>
            <h5>Funcionalidades</h5>
            <hr>
            <sara-linkmodal icone="fas fa-plus" label="Adicionar" target="#modalFunc"></sara-linkmodal>
            <sara-modal idmodal="modalFunc" iddialog="dialogFunc" titulo="Permissões">
                <span slot="modal-body">
                    <sara-input  icon="fas fa-search">
                        <input name="busca" type="text" placeholder="Buscar...." class="form-control" autofocus 
                        v-model="busca" >
                    </sara-input>
                    <sara-tabelapaginada :link="this.link" :header="this.header" :busca="this.busca" 
                                                        sort="nomeTela" :apagar="false" :editar="false">
                    </sara-tabelapaginada>
                </span>
                <span slot="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Fechar</button>
                </span>
            </sara-modal>
            <div v-if="this.dadosStore.itens.length > 0">
                <sara-tabela  :conteudotabela="dadosStore.itens" :header="header" filtroinicial="nomeTela" 
                    :select="false" :issort="false" :exceto="['idTelas','siglaTela']" :apagar="true" :editar="false"></sara-tabela>
                </div>
        </div>
    </span>
</sara-card>
</template>

<script>
    export default {
        props:['idmodal', 'iddialog', 'titulo', 'link'],
        data: function () {
            return {
                dadosStore: this.$store.state,
                header: [{id:'nomeTela', label: 'Permissões'}],
                busca: '',
            }
        },
        methods: {
        }
    }
</script>
