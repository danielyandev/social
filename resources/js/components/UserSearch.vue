<template>
    <div class="card col-md-8">
        <div class="card-body">
            <input v-model="search_phrase" @input="search" type="text" class="form-control" placeholder="Type to search users">
            <p class="text-center text-info" v-if="show_search_hint">Min. 3 chars</p>
            <ul class="list-unstyled mt-4">
                <li v-for="user in search_results" class="media mt-4">
                    <img :src="user.avatar" class="mr-3" alt="" style="max-width: 50px">
                    <div class="media-body">
                        <h6 class="mt-0 mb-1">
                            {{ user.name + ' ' + user.surname }}
                            <span v-if="auth_user.id === user.id"> (You)</span>
                        </h6>
                        <router-link :to="{name: 'user', params: {id: user.id}}" class="ard-link">View profile</router-link>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "UserSearch",
        data() {
            return {
                search_phrase: '',
                search_phrase_min_length: 3,
                search_results: []
            }
        },
        computed: {
            ...mapGetters({
                auth_user: 'auth/user',
            }),
            show_search_hint: function () {
                return this.search_phrase.length < this.search_phrase_min_length
            }
        },
        methods: {
            search: async function () {
                if (this.search_phrase.length < this.search_phrase_min_length){
                    this.search_results = []
                    return false
                }

                const {data} = await axios.get('/users/search', {params: {phrase: this.search_phrase}});
                this.search_results = data.data

            }
        }
    }
</script>
