<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <router-link :to="{name: 'main'}" class="navbar-brand">Social network</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar-->
                <ul v-if="user" class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ user.name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a @click.prevent="logout" class="dropdown-item" href="#" role="button">
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <ul v-else class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    <li class="nav-item">
                        <router-link :to="{name: 'login'}" class="nav-item nav-link">Login</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'register'}" class="nav-item nav-link">Register</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    import {mapGetters} from 'vuex'
    export default {
        name: "VHeader",
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

            logout: async function () {
                // Log out the user.
                await this.$store.dispatch('auth/logout')

                // Refresh page
                window.location.reload()
            }
        }
    }
</script>
