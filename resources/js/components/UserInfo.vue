<template>
    <div class="card col-md-8">
        <img :src="user.avatar" class="card-img-top" alt="">
        <div class="text-center mt-3">
            <h5 class="card-title">{{ user.name + ' ' + user.surname }}</h5>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Joined: {{ user.join_date }}</li>
            <li class="list-group-item">Friends: 0</li>
        </ul>
        <div class="card-body" v-if="showLogout">
            <a @click.prevent="logout" href="#" class="card-link">Logout</a>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "UserInfo",
        props: ['user'],
        computed: {
            ...mapGetters({
                auth_user: 'auth/user',
            }),
            showLogout: function () {
                return this.user.id === this.auth_user.id
            }
        },
        methods: {
            logout: async function () {
                // Log out the user.
                await this.$store.dispatch('auth/logout')

                // Refresh page
                window.location.reload()
            }
        }
    }
</script>
