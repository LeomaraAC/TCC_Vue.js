<template>
  <v-snackbar  v-model="showMessage" :timeout="timeout" :color="color" >
    <span>{{msg}}</span>
    <v-btn flat color="accent" @click.native="showMessage = false">Fechar</v-btn>
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
      },
      show: {
        type: Boolean,
        default: true
      }
    },
    data () {
        return {
          color: this.cor || '',
          timeout: 5000,
          showMessage: this.show
        }
    },
    watch: {
      show: function() {
        if(this.show)
          this.showMessage = this.show;
      }
    }
}
</script>