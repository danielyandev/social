<template>
    <div id="bg"></div>
</template>

<script>
    export default {
        name: "Welcome"
    }
</script>

<style scoped>
    #bg {
        background-image: url("/img/bg.jpg");
        background-size: cover;
        height: 100vh;
        position: absolute;
        top: 0;
        z-index: -1;
    }
</style>
