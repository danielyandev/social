<template>
    <div>
        <feed-textarea></feed-textarea>

        <div class="mt-3">
            <div v-for="post in posts" class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-md-1">
                        <img src="http://social.loc/img/default.png" class="card-img ml-3 mt-3" alt="" style="max-width: 80px">
                    </div>
                    <div class="col-md-10 ml-3">
                        <div class="card-body">
                            <h5 class="card-title">Name Surname</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <p class="card-text"><small class="text-muted">Posted 3 mins ago</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FeedTextarea from "./FeedTextarea";
    export default {
        name: "Feed",
        components: {FeedTextarea},
        data() {
            return {
                // todo fetch posts
                posts: [1]
            }
        }
    }
</script>
