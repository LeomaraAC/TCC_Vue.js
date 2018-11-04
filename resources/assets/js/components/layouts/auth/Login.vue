<template>
    <form method="post" v-bind:action="action" novalidate v-on:submit="validaForm">
        <input type="hidden" name="_token" v-bind:value="token">
        <div class="login-form">
            <div class="row justify-content-center mb-3">
                <h1><span class="font">SARA</span><small> Campus Capivari</small></h1>
            </div>
            <!-- Campos do formulário -->
            <!-- Usuário -->
            <s-input  icon="fas fa-user-tie">
                <input v-model="user"  v-validate="'required'" name="prontuario" type="text" 
                    placeholder="Usuário"  data-vv-validate-on="focusout|input" autofocus
                    :class="{'form-control form-control-warning': true, 'is-invalid': errors.has('prontuario') }"
                >
                <span slot="error">
                    {{ errors.first('prontuario') }}
                </span>
            </s-input>
            <!-- /Usuário -->
            <!-- Senha -->
            <s-input  icon="fas fa-key">
                <input  v-validate="'required'" name="password" type="password" placeholder="Senha" 
                    data-vv-validate-on="focusout|input"
                    :class="{'form-control': true, 'form-control-warning': true, 'is-invalid': errors.has('password') }"
                >
                <span slot="error">
                    {{ errors.first('password') }}
                </span>
            </s-input>
            <!-- /Senha -->
            <!-- Remember -->
            <div class="checkbox text-center">
                <s-checkbox name="remember" label="Mantenha-me conectado" :ischecked="false"></s-checkbox>
            </div>
            <!-- /Remember -->
            <button type="submit" class="log-btn btn-primary">Log in </button>
            <p class="mt-2 mb-2 text-muted text-center"><a v-bind:href="url">Recuperar senha ›</a></p>
        </div>
    </form>
</template>

<script>
export default {
    props: ['old', 'url', 'token', 'action'],
    data: function(){
        return {
          user: this.old || ''
        }
      },
    methods: {
        validaForm: function (event) { 
            this.$validator.validateAll().then((result) => {
                if (!result) {
                    event.preventDefault();
                }
            });
        }
    }
};
</script>
<style scoped>
    .login-form {
        width: 400px;
        padding: 40px 30px;
        background: #eee;
        border-radius: 4px;
        margin: auto;
        position: absolute;
        left: 0;
        right: 0;
        top: 25%;
    }
    .log-btn {
        margin-top: 20px;
        width: 100%;
        font-size: 16px;
        height: 50px;
        text-decoration: none;
        border: none;
        border-radius: 4px;
    }

</style>

