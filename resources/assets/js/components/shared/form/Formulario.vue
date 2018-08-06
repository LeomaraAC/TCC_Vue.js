<template>
    <form :id="id" :action="action" :method="defineMethod" @submit.prevent="submit">
    <input v-if="alterMethod" type="hidden" name="_method" :value="alterMethod">
    <input  type="hidden" name="_token" :value="token">
    <slot></slot>
  </form>
</template>
<script>
export default {
    props: ['action', 'token', 'method', 'id'],
    data: function () {
        return {
            alterMethod: ''
        }
    },
    computed: {
        defineMethod: function () {
            if(this.method.toLowerCase() == 'post' || this.method.toLowerCase() == 'get')
                return this.method.toLowerCase();
            if(this.method.toLowerCase() == 'put' )
                this.alterMethod = 'put';
            if(this.method.toLowerCase() == 'delete' )
                this.alterMethod = 'delete';
            return 'post';
        }
    },
    methods: {
        submit: function () {
            this.$emit('submit');
        }
    }
}
</script>
