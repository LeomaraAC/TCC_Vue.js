<template>
  <v-snackbar v-model="show" :timeout="timeout" :color="color">
    {{message}}
    <v-btn flat color="accent" @click.native="show = false">Close</v-btn>
  </v-snackbar>
</template>

<script>
export default {
    props:['cor', 'msg'],
    data () {
        return {
        show: true,
        message: this.msg || '',
        color: this.cor || '',
        timeout: 4000,
        }
    }
}
</script>