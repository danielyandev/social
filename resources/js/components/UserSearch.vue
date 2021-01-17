<template>
    <div class="card col-md-8">
        <div class="card-body">
            <input v-model="search_phrase" @input="search" type="text" class="form-control" placeholder="Type to search users">
            <p class="text-center text-info" v-if="show_search_hint">Min. 3 chars</p>
            <ul class="list-unstyled mt-4">
                <li v-for="user in search_results" class="media mt-4">
                    <img :src="user.avatar" class="mr-3" alt="" style="max-width: 50px">
                    <div class="media-body">
                        <h6 class="mt-0 mb-1">{{ user.name + ' ' + user.surname }}</h6>
                        <a href="#" class="ard-link">View profile</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
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

                // todo make request
                // const {data} = await axios.get()
                const data = [
                    {
                        id: 11,
                        name: 'Ruben',
                        surname: 'Danielyan',
                        avatar: 'http://social.loc/img/default.png',
                    },
                    {
                        id: 12,
                        name: 'Other',
                        surname: 'User',
                        avatar: 'http://social.loc/img/default.png',
                    }
                ]
                this.search_results = data

            }
        }
    }
</script>
