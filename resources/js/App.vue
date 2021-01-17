<template>
    <div v-if="ready">
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
        async mounted() {
            await this.checkUser()
        },
        data(){
            return {
                ready: false
            }
        },
        computed: {
            ...mapGetters({
                user: 'auth/user',
                access_token: 'auth/access_token',
            })
        },
        methods: {
            checkUser: async function () {
                if (this.access_token && !this.user){
                    // todo check if refresh needed
                    await this.$store.dispatch('auth/fetchUser')
                }

                this.ready = true
            },
        }
    }
</script>

<style scoped>

</style>
