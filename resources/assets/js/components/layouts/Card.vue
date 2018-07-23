<template>
    <div class="card text-center">
        <div class="card-header text-left" v-if="titulo">
           <h3><strong>{{titulo}}</strong></h3>
        </div>
        <div class="card-body">
            <slot name="body"></slot>
        </div>
        <div class="card-footer text-left" v-if="footer == true">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props:['titulo','footer']
    }
</script>
<style scoped>
    /* .header {
        font-size: 
    } */
</style>

