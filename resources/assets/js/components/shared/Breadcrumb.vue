<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li v-for="item in caminhos" :key="item.titulo" :class="{'breadcrumb-item':true, 'active': !item.url}">
                <a v-bind:href="item.url" v-if="item.url">{{item.titulo}}</a>
                <span v-if="!item.url">{{item.titulo}}</span>
            </li>
        </ol>
    </nav>
</template>
<script>
    export default {
        props:['caminhos'],
        computed: {
            isActive: function () {
                console.log(this.item);
                return false;
            }
        }
    }
</script>
