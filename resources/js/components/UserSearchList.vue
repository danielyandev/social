<template>
    <div>
        <input v-model="search_phrase" @input="search" type="text" class="form-control" placeholder="Type to search users">

        <ul class="list-unstyled mt-4">
            <li v-for="user in search_results.data" class="media mt-4">
                <img :src="user.avatar" class="mr-3" alt="" style="max-width: 50px">
                <div class="media-body">
                    <h6 class="mt-0 mb-1">
                        {{ user.name + ' ' + user.surname }}
                    </h6>
                    <a @click.prevent="show_user_page(user.id)" href="#" class="card-link">View profile</a>
                </div>
            </li>
        </ul>

        <div v-if="search_results.meta.last_page > 1">
            <button class="btn btn-sm btn-outline-success" :disabled="!show_prev_page" @click="change_page(1)">First</button>
            <button class="btn btn-sm btn-outline-success" :disabled="!show_prev_page" @click="change_page(search_results.meta.current_page - 1)">Prev</button>
            <button class="btn btn-sm btn-success" disabled>{{ search_results.meta.current_page }}</button>
            <button class="btn btn-sm btn-outline-success" :disabled="!show_next_page" @click="change_page(search_results.meta.current_page + 1)">Next</button>
            <button class="btn btn-sm btn-outline-success" :disabled="!show_next_page" @click="change_page(search_results.meta.last_page)">Last</button>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import Pagination from "./Pagination";

    export default {
        name: "UserSearchList",
        components: {Pagination},
        props: ['modalCloseCallback', 'type'],
        data() {
            return {
                search_phrase: '',
                search_results: {
                    users: [],
                    links: {},
                    meta: {}
                }
            }
        },
        mounted() {
            if (this.search_friends){
                this.fetch_friends()
            }
        },
        computed: {
            ...mapGetters({
                auth_user: 'auth/user',
            }),
            show_prev_page: function () {
                return this.search_results.meta.current_page > 1
            },
            show_next_page: function () {
                return this.search_results.meta.current_page < this.search_results.meta.last_page
            },
            search_friends: function () {
                return this.type === 'friends' ? 1 : 0
            }
        },
        methods: {
            show_user_page: function (id) {
                // close modal if open
                if (typeof this.modalCloseCallback === "function"){
                    this.modalCloseCallback()
                }
                this.$router.push({
                    name: 'user',
                    params: {id}
                })
            },
            search: async function () {
                if (!this.search_phrase){
                    this.reset_search_results()
                    return false
                }
                const {data} = await axios.get('/users/search?friends=' + this.search_friends, {params: {phrase: this.search_phrase}});
                this.search_results = data
            },
            reset_search_results: function(){
                this.search_results = {
                    users: [],
                    links: {},
                    meta: {}
                }
            },
            fetch_friends: async function (page = 1) {
                const {data} = await axios.get('/user/friends?page=' + page)
                this.search_results = data
            },
            change_page: async function (page = 1, search = false) {
                let url = ''
                if (this.search_friends){
                    if (this.search_phrase.length){
                        url = '/users/search?friends=1&phrase=' + this.search_phrase
                    }else{
                        url = '/user/friends'
                    }
                }else{
                    url = '/users/search?phrase=' + this.search_phrase
                }

                const {data} = await axios.get(url, {params: {page}})
                this.search_results = data
            }
        },
    }
</script>

<style scoped>

</style>
