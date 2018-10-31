<template>
    <s-input  icon="fas fa-search" :btn="true">
        <input name="busca" type="text" placeholder="Buscar...." class="form-control" :autofocus="focus"
            v-model="search" @keyup.enter="filtrar" @blur="filtrar">
    </s-input>
</template>
<script>
export default {
    props:{
        focus: {
            default: true,
            type: Boolean
        }
    },
    data: function () {
        return {
            search: ''
        }
    },
    methods:{
        filtrar: function () {
            Event.fire('filtrar', this.search);
        }
    },
    mounted: function() {
        Event.listen('btnInput', () => {
            this.filtrar()
        });
    }
}
</script>

