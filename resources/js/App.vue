<template>
    <div class="container-fluid">
        <v-header></v-header>
        <br>
        <router-view></router-view>
    </div>
</template>

<script>
    import VHeader from "./partials/VHeader";
    export default {
        name: "App",
        components: {VHeader},
    }
</script>

<style scoped>

</style>
