<template>
    <div class="card col-md-8">
        <div class="card-body">
            <user-search-list type="users"></user-search-list>
        </div>
    </div>
</template>

<script>
    import UserSearchList from "./UserSearchList";
    export default {
        name: "UserSearch",
        components: {UserSearchList},
    }
</script>
