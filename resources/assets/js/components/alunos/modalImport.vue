<template>
    <div>
        <button type="button" class="btn btn-outline-info" @click="show"><i class="fas fa-file-import"></i> Importar dados</button>
        <s-modal name="import" title="Importar dados dos alunos">
            <span slot="modal-body">
                <s-formulario action="/import/alunos" :token="token"  ref="form"
                                    method="POST" id="importacao">
                    <s-import ref="importar_aluno" @validado="hasFIle = $event" />
                </s-formulario>
            </span>
            <span slot="modal-footer">
                <button type="button" class="btn btn-outline-primary" @click="importar_dados">Importar</button>
                <button type="button" class="btn btn-outline-danger" @click="closeModal">Fechar</button>
            </span>
        </s-modal>
    </div>
</template>
<script>
export default {
    props: {
        token: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            hasFIle: false
        }
    },
    methods: {
        show() {
          this.$modal.show("import");
        },
        closeModal() {
            this.$modal.hide("import");
        },
        importar_dados() {
            this.$refs.importar_aluno.validaSubmit();
            if(this.hasFIle)           
                this.$refs.form.$el.submit();
        },
        
    }
}
</script>
