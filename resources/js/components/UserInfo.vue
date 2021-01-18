<template>
    <div v-if="user" class="card col-md-8">
        <img :src="user.avatar" class="card-img-top avatar" alt="">
        <div class="text-center mt-3">
            <h5 class="card-title">{{ user.name + ' ' + user.surname }}</h5>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Joined: {{ user.join_date }}</li>
            <li class="list-group-item">
                Friends: {{ user.friends_count }}
                <b-button v-if="current_user" @click="open_friends_modal" size="sm" variant="success" class="float-right">
                    View
                </b-button>
            </li>
            <li v-if="current_user" class="list-group-item">Requests: {{ user.friend_requests_count }}</li>
        </ul>
        <div v-if="current_user" class="card-body">
            <a @click.prevent="logout" href="#" class="card-link">Logout</a>
        </div>
        <div v-else class="card-body">
            <p v-if="relationship.is_friend">
                <a @click.prevent="unfriend" href="#" class="card-link text-danger">Unfriend</a>
            </p>
            <p v-else-if="relationship.requested" class="text-info">Requested</p>
            <p v-else-if="relationship.rejected" class="text-danger">Rejected</p>
            <p v-else-if="relationship.pending_confirmation">
                <span>User sent you friend request</span>
                <a @click.prevent="respond_request('accepted')" href="#" class="card-link text-success">Accept</a>
                <a @click.prevent="respond_request('rejected')" href="#" class="card-link text-danger">Reject</a>
            </p>
            <p v-else>
                <a @click.prevent="send_friend_request" href="#" class="card-link text-success">Add friend</a>
            </p>
        </div>

        <b-modal v-model="show_friends_modal" id="modal-friends" title="Friends" scrollable :hide-footer="true">
            <user-search-list type="friends" :modal-close-callback="close_friends_modal"></user-search-list>
        </b-modal>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import UserSearchList from "./UserSearchList";

    export default {
        name: "UserInfo",
        components: {UserSearchList},
        props: ['user'],
        data() {
            return {
                show_friends_modal: false,
                friends: []
            }
        },
        computed: {
            ...mapGetters({
                auth_user: 'auth/user',
            }),
            current_user: function () {
                return this.user.id === this.auth_user.id
            },
            relationship: function () {
                return {
                    is_friend: this.user.relationship.status === 'accepted',
                    requested: this.user.relationship.status === 'pending' && !this.user.relationship.is_sender,
                    rejected: this.user.relationship.status === 'rejected',
                    pending_confirmation: this.user.relationship.status === 'pending' && this.user.relationship.is_sender
                }
            }
        },
        methods: {
            logout: async function () {
                // Log out the user.
                await this.$store.dispatch('auth/logout')

                // Refresh page
                window.location.reload()
            },
            send_friend_request: async function () {
                const {data} = await axios.post('/relationships', {user_id: this.user.id})
                this.user.relationship.status = data.data.status
                this.user.relationship.is_sender = false
            },
            unfriend: async function () {
                await axios.delete('/relationships/' +  this.user.relationship.id)
                this.user.relationship.status = null
                this.user.relationship.is_sender = null
            },
            respond_request: async function (status) {
                const {data} = await axios.put('/relationships/' +  this.user.relationship.id, {status})
                this.user.relationship.status = data.data.status
            },
            open_friends_modal: async function () {
                this.show_friends_modal = true
            },
            close_friends_modal: function () {
                this.show_friends_modal = false
            },
        }
    }
</script>

<style scoped>
    .avatar {
        max-width: 120px;
        margin: 0 auto;
    }
</style>
