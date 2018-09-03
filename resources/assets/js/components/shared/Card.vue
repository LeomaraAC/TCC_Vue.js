<template>
    <div class="card text-center">
        <div class="card-header text-left" v-if="titulo">
            <div class="row">
                <h4 class="mr-3 ml-3"><i :class="icon"></i> </h4>
                <h4> {{titulo}}</h4>
            </div>
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

