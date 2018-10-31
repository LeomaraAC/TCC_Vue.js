<template>
    <div>
        <div class="row">
        <div class="offset-md-2 col-md-8 col-sm-12">
            <s-pesquisa :focus="false"></s-pesquisa>
        </div>
        </div>
        <div class="row">
            <div class="offset-md-3 col-md-6 col-sm-12">
                <span><b>Responsabilidade:</b>&emsp;</span>
                <s-radio name="responsavel" value="todos" label="Todos" :ischecked="true" @checked="setValue"/>
                <s-radio name="responsavel" value="particular" label="Particular" @checked="setValue"/>
                <s-radio name="responsavel" value="setor" label="Setor" @checked="setValue" />
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: function() {
        return{
            responsavel: 'todos',
            termo: ''
        }
    },
    mounted: function() {
        Event.listen('filtrar', termoBusca => {
            this.termo = termoBusca;
            this.filtrar();
        });
    },
    methods: {
        setValue: function(value) {
            this.responsavel = value;
            this.filtrar();
        },
        filtrar: function () {
            Event.fire('filtrarAgendamento', [this.termo, this.responsavel]);
        }
    }
}
</script>
