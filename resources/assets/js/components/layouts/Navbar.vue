<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn-toggled"  v-on:click="toogled">
                <i class="fas fa-align-left"></i>
            </button>
            <ul class="navbar-nav ml-auto ">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" 
                    aria-haspopup="true" aria-expanded="false" >
                        {{ user }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/user/trocar_senha">Trocar Senha</a>
                        <form id="logout-form" v-bind:action="logout" method="POST">
                            <input type="hidden" name="_token" v-bind:value="token">
                            <a class="dropdown-item" href="#" v-on:click="executaLogout">
                                Logout
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        props: ['logout', 'token', 'user'],
        data: function(){
            return {
            ativo: false
            }
        },
        methods: {
            toogled: function () {
                this.ativo = !this.ativo;
                Event.fire('toggled', this.ativo);
            },
            executaLogout: function () {
                document.getElementById('logout-form').submit();
            }
        }
    }
</script>
<style scoped>
    .navbar {
        padding: 15px 10px;
        background: #169410 !important;
        border: none;
        border-radius: 0;
        margin-bottom: 0px;
        /* box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1); */
        box-shadow: 10px 6px 16px 1px rgba(0,0,0,0.62);
    }

    .btn-toggled {
        background-color: transparent;
        border: none;
        color: #fff;
        font-size: 20px;
    }
    .btn-toggled:focus {
        outline: thin dotted;
            outline: 0px auto -webkit-focus-ring-color;
            outline-offset: 0px;
    }
    .nav-link {
        color: #fff !important;
    }
    /* .dropdown-menu {
        background-color: #179910;
    } */
</style>

