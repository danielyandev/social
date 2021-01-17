<template>
    <div class="row">
        <div class="col-md-3 d-flex justify-content-center">
            <user-info :user="user"></user-info>
        </div>
        <div class="col-md-6">
            <feed-textarea></feed-textarea>
            <feed></feed>
        </div>
        <div class="col-md-3 d-flex justify-content-center">
            <user-search></user-search>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import UserInfo from "../components/UserInfo";
    import FeedTextarea from "../components/FeedTextarea";
    import UserSearch from "../components/UserSearch";

    export default {
        name: "Home",
        components: {UserSearch, FeedTextarea, UserInfo},
        computed: {
            ...mapGetters({
                user: 'auth/user',
            })
        }
    }
</script>
