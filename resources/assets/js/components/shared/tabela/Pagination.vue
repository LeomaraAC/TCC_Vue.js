<template>
    <div class="text-xs-center">
        <v-pagination
        v-model="page"
        :length="pages"
        circle
        color='green'
        :total-visible="7"
        @input="navigate"
      ></v-pagination>
    </div>
</template>
<script>
    export default {
        props: ['pages'],
        data: function () {
            return {
                page: 1
            }
        },
        methods: {
            navigate( page) {
                this.$emit("navigate", page);
            }
        }
    }
</script>
<style>
/* class=" v-pagination__navigation--disabled"*/
    li button.v-pagination__item,
    li button.v-pagination__navigation {
        outline: none;
    }
    li button.v-pagination__item::-moz-focus-inner,
    li button.v-pagination__navigation::-moz-focus-inner {
        border: 0;
    }
</style>
