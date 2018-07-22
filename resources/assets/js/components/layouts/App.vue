<template>
    <div class="wrapper application ">
        <s-sidebar v-if="login === 'true'" :toggled="isToggled"></s-sidebar>
        <div id="content">
            <s-navbar v-if="login === 'true'" @toggled="toggle" :logout="logout" 
                v-bind:token="token" v-bind:user="user"></s-navbar>
            <main class="py-4">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['login', 'logout', 'token', 'user'],
        data () {
            return {
                isToggled: false
            }
        },
        methods: {
            toggle(e) {
                this.isToggled = e;
            }
        }
    }
</script>
