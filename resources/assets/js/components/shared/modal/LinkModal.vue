<template>
    <button  class="btn btn-outline-success" data-toggle="modal" v-bind:data-target="target">
                <i v-bind:class="icone"></i>  {{label}}
    </button>
</template>

<script>
    export default {
        props:['icone', 'label', 'target']
    }
</script>
