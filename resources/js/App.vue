<template>
    <div class="container-fluid">
        <v-header :user="user"></v-header>
        <br>
        <router-view></router-view>
    </div>
</template>

<script>
    import VHeader from "./partials/VHeader";
    import {mapGetters} from "vuex";
    export default {
        name: "App",
        components: {VHeader},
        mounted() {
            this.checkUser()
        },
        computed: {
            ...mapGetters({
                user: 'auth/user',
                access_token: 'auth/access_token',
            })
        },
        methods: {
            checkUser: function () {
                if (this.access_token && !this.user){
                    // todo check if refresh needed
                    this.$store.dispatch('auth/fetchUser')
                }
            },
        }
    }
</script>

<style scoped>

</style>
