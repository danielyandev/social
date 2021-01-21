<template>
    <ul class="list-unstyled mt-4">
        <li v-for="user in users" class="media mt-4">
            <img :src="user.avatar" class="mr-3" alt="" style="max-width: 50px">
            <div class="media-body">
                <h6 class="mt-0 mb-1">
                    {{ user.name + ' ' + user.surname }}
                </h6>
                <a @click.prevent="show_user_page(user.id)" href="#" class="card-link">View profile</a>
                <div v-if="user.relationship.status === 'pending'">
                    <a @click.prevent="respond_request(user.relationship.id, 'accepted')" href="#" class="card-link text-success">Accept</a>
                    <a @click.prevent="respond_request(user.relationship.id, 'rejected')" href="#" class="card-link text-danger">Reject</a>
                </div>
                <p v-else-if="user.relationship.status === 'rejected'" class="text-danger">Rejected</p>
                <p v-else-if="user.relationship.status === 'accepted'" class="text-success">Accepted</p>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "FriendRequestsList",
        props: ['modalCloseCallback'],
        mounted() {
            this.fetch_requests()
        },
        data() {
            return {
                users: []
            }
        },
        methods: {
            fetch_requests: async function () {
                const {data} = await axios.get('/relationships')
                this.users = data.data
            },
            show_user_page: function (id) {
                // close modal if open
                if (typeof this.modalCloseCallback === "function"){
                    this.modalCloseCallback()
                }
                this.$router.push({
                    name: 'user',
                    params: {id}
                })
            },
            respond_request: async function (id, status) {
                const {data} = await axios.put('/relationships/' + id, {status})
                this.users.map(user => {
                    if (user.relationship.id === id){
                        user.relationship.status = data.data.status
                    }
                })
                if (status === 'accepted'){
                    await this.$store.dispatch('auth/incrementFriendsCount')
                }

                await this.$store.dispatch('auth/decrementFriendRequestsCount')
            },
        }
    }
</script>
