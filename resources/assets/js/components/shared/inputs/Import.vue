<template>
    <div>
        <div class="large-12 medium-12 small-12 filezone" v-show="file == null">
            <input type="file" id="files" name="files" ref="files" @change="handleFiles()"
                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
            <p>
                Solte seu arquivo aqui <br>ou clique para pesquisar
            </p>
        </div>

        <div class="file-listing" v-if="file != null">
            <img class="preview" src="/images/generic.png" />
            {{ file.name }}
            <div class="remove-container">
                <a class="remove" @click="removeFile">Remover</a>
            </div>
        </div>
        <v-snackbar v-model="erro" :timeout="4000" color="red">
            {{message}}
            <v-btn flat color="accent" @click.native="erro = false">Fechar</v-btn>
        </v-snackbar>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            file: null,
            erro: false,
            message: ''
        }
    },
    methods: {
        handleFiles() {
            console.log('função');
            
            let upload = this.$refs.files.files[0];
            if(upload != undefined){
                if(upload.type != 'application/vnd.ms-excel' && 
                 upload.type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    this.message = 'Somente arquivos .csv, .xlsx e .xls';
                    this.erro = true;
                } else
                    this.file = upload;
            }
        },
        removeFile( key ){
            this.$refs.files.value = '';
            
            this.file = null;
        },
        validaSubmit() {
            if(this.file == null){
                this.message = 'Escolha um arquivo para importar.';
                this.erro = true;
                this.$emit('validado', false);
            }
            else
                this.$emit('validado', true);
        }
    }
}
</script>
<style>
    input[type="file"]{
        opacity: 0;
        width: 100%;
        height: 200px;
        position: absolute;
        cursor: pointer;
    }
    .filezone {
        outline: 2px dashed grey;
        outline-offset: -10px;
        background: #ccc;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px;
        position: relative;
        cursor: pointer;
    }
    .filezone:hover {
        background: #c0c0c0;
    }

    .filezone p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 50px 50px 50px;
    }

    div.file-listing img{
        max-width: 90%;
    }

    div.file-listing{
        margin: auto;
        padding: 10px;
    }

    div.file-listing img{
        height: 100px;
    }
    div.success-container{
        text-align: center;
        color: green;
    }

    div.remove-container{
        text-align: center;
    }

    div.remove-container a{
        color: crimson !important;
        cursor: pointer;
    }
</style>

