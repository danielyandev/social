<template>
    <div class="row">
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
    import {mapGetters} from "vuex";
    import UserInfo from "../components/UserInfo";
    import Feed from "../components/Feed";
    import UserSearch from "../components/UserSearch";

    export default {
        name: "Home",
        components: {UserSearch, UserInfo, Feed},
        computed: {
            ...mapGetters({
                user: 'auth/user',
            })
        }
    }
</script>
