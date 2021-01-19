<template>
    <div>
        <feed-textarea v-if="show_textarea" :user="user"></feed-textarea>

        <div class="mt-3">
            <div v-if="posts.length">
                <div v-for="post in posts" class="card mb-3">
                    <div class="row no-gutters">
                        <div class="col-md-1">
                            <img :src="post.posted_user.avatar" class="card-img ml-3 mt-3" alt="" style="max-width: 80px">
                        </div>
                        <div class="col-md-10 ml-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ post.posted_user.name + ' ' + post.posted_user.surname }}</h5>
                                <p class="card-text">{{ post.text }}</p>
                                <p class="card-text">
                                    <small class="text-muted">Posted {{ post.created_at }}</small>
                                    <small class="text-muted float-right">Visible for {{ post.is_public ? 'public' : 'friends' }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <infinite-loading @infinite="load_more"></infinite-loading>
            </div>
            <p v-else class="text-center">Nothing in feed</p>
        </div>
    </div>
</template>

<script>
    import FeedTextarea from "./FeedTextarea"
    import {mapGetters} from "vuex"
    import InfiniteLoading from 'vue-infinite-loading'
    export default {
        name: "Feed",
        components: {FeedTextarea, InfiniteLoading},
        props: ['user'],
        async mounted() {
            await this.fetch_posts()
        },
        data() {
            return {
                posts: []
            }
        },
        computed: {
            ...mapGetters({
                auth_user: 'auth/user'
            }),
            show_textarea: function () {
                return this.auth_user.id === this.user.id || this.user.relationship.status === 'accepted';


            },
            posts_url: function () {
                return '/users/' + this.user.id + '/posts'
            }
        },
        methods: {
            add_post: function (post) {
                this.posts.unshift(post)
            },
            fetch_posts: async function () {
                try{
                    const {data} = await axios.get(this.posts_url)
                    this.posts = data.data
                    // console.log(this.posts)
                }catch (e) {
                    // todo handle error
                }
            },
            load_more: async function ($state) {
                const {data} = await axios.get(this.posts_url, {params: {skip: this.posts.length}})
                if (data.data.length) {
                    this.posts.push(...data.data);
                    $state.loaded();
                } else {
                    $state.complete();
                }
            }
        },
        watch: {
            user: async function () {
                await this.fetch_posts()
            }
        }
    }
</script>
