<template>
    <div class="row" v-if="user">
        <div class="col-md-3 d-flex justify-content-center">
            <user-info :user="user"></user-info>
        </div>
        <div class="col-md-6">
            <feed :user="user"></feed>
        </div>
        <div class="col-md-3 d-flex justify-content-center">
            <user-search></user-search>
        </div>
    </div>
</template>

<script>
    import UserInfo from '../components/UserInfo'
    import Feed from '../components/Feed'
    import UserSearch from '../components/UserSearch'
    import {mapGetters} from "vuex";
    export default {
        name: "User",
        props: ['id'],
        components: {UserInfo, Feed, UserSearch},
        created() {
            if (!this.logged_in){
                this.allow_fetch = false
                this.$router.push({name: 'main'})
            }
        },
        async mounted() {
            await this.fetch_user()
        },
        data(){
            return {
                user: null,
                allow_fetch: true
            }
        },
        computed: {
            ...mapGetters({
                logged_in: 'auth/logged_in',
            })
        },
        watch: {
            id: async function () {
                await this.fetch_user()
            }
        },
        methods: {
            fetch_user: async function () {
                if (this.allow_fetch){
                    const {data} = await axios.get('/users/' + this.id)
                    this.user = data.data
                }
            }
        }
    }
</script>

<style scoped>

</style>
