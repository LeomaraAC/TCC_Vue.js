<template>
    <div class="card text-center">
        <div class="card-header row text-left" v-if="titulo">
            <h4 class="mr-3 ml-3"><i :class="icon"></i> </h4>
           <h3><strong> {{titulo}}</strong></h3>
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
        props:['titulo','footer', 'icon']
    }
</script>

