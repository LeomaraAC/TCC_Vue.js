<template>
  <v-snackbar v-model="show" :timeout="timeout" :color="color">
    <span>{{message}}</span>
    <v-btn flat color="accent" @click.native="show = false">Fechar</v-btn>
  </v-snackbar>
</template>

<script>
export default {
    props:{
      cor: {
        type: String,
        required: true
      },
      msg:{
        type: String,
        required: true
      }
    },
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