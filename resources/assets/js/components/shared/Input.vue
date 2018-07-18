<template>
<div class="form-group mb-3">
    <div class="input-group">
        <slot></slot>
        <div class="input-group-append" v-if="icon">
            <span class="input-group-text"><i v-bind:class="icon"></i></span>
        </div>
    </div>
    <div class="erro pl-1">
        <slot name="error"></slot>
    </div>
    </div>
</template>

<script>
    export default {
        props: [ "icon"]
    }
</script>
<style scoped>
    .input-group-text {
        background-color: white;
    }    
    .erro {
        color: crimson;
    }
</style>