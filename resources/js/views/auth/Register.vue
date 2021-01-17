<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>

                    <div class="card-body">
                        <b-alert variant="danger" :show="errors.length > 0" dismissible @dismissed="errors=[]">
                            <ul class="list-unstyled">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </b-alert>

                        <b-alert variant="success" :show="registered">
                            You are successfully registered, now you can <a href="/login">Sign In</a> with your credentials
                        </b-alert>

                        <form @submit.prevent="register">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                <div class="col-md-6">
                                    <input v-model="form.name" id="name" type="text" class="form-control" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="surname" class="col-md-4 col-form-label text-md-right">Surname</label>

                                <div class="col-md-6">
                                    <input v-model="form.surname" id="surname" type="text" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input v-model="form.email" id="email" type="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input v-model="form.password" id="password" type="password" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                <div class="col-md-6">
                                    <input v-model="form.password_confirmation" id="password-confirm" type="password" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary" v-bind:class="{ disabled: formSubmitted }">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Cookies from 'js-cookie'
    import {mapGetters} from "vuex";
    export default {
        name: "Register",
        data() {
            return {
                form: {
                    name: '',
                    surname: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
                errors: [],
                registered: false,
                formSubmitted: false,
            }
        },
        computed: {
            ...mapGetters({
                logged_in: 'auth/logged_in',
            })
        },
        created() {
            if (this.logged_in){
                this.$router.push({name: 'main'})
            }
        },
        methods: {
            register: async function(e){
                if (this.formSubmitted){
                    return false
                }

                this.formSubmitted = true
                this.errors = [];
                try{
                    await axios.post('/register', this.form)
                    this.registered = true
                }catch (error) {
                    let errors = await error.response.data.errors
                    this.errors = Object.values(errors).flat()
                    this.formSubmitted = false
                }
            }
        }
    }
</script>
