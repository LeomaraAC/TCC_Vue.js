<template>
    <s-input  icon="fas fa-search" :btn="true">
        <input name="busca" type="text" placeholder="Buscar...." class="form-control" autofocus
            v-model="search" @keyup.enter="filtrar">
    </s-input>
</template>
<script>
export default {
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

