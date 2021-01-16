<template>
    <home v-if="loggedIn"></home>
    <welcome v-else></welcome>
</template>

<script>
    import Home from "./Home";
    import Welcome from "./Welcome";
    import Cookies from 'js-cookie'
    export default {
        name: "Main",
        components: {Welcome, Home},
        computed: {
            loggedIn: function () {
                return !!Cookies.get('access_token')
            }
        }
    }
</script>
