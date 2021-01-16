<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <b-alert variant="danger" :show="errors.length > 0" dismissible @dismissed="errors=[]">
                            <ul class="list-unstyled">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </b-alert>

                        <b-alert variant="success" :show="loggedIn">
                            Login successful, redirecting
                        </b-alert>
                        <form @submit.prevent="login">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input v-model="form.email" id="email" type="email" class="form-control" required autocomplete="email" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input v-model="form.password" id="password" type="password" class="form-control" required autocomplete="current-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Login</button>
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
    export default {
        name: "Login",
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                },
                errors: [],
                loggedIn: false,
                formSubmitted: false,
            }
        },

        methods: {
            redirect: function (url = '/') {
                window.location.href = url
            },

            login: async function () {
                if (this.formSubmitted){
                    return false
                }

                this.formSubmitted = true
                this.errors = [];
                try{
                    const { data } = await axios.post('/login', this.form)
                    const {access_token, refresh_token, expires_in} = data.data
                    if (!access_token){
                        this.errors = ['Something went wrong, please try again later']
                    }

                    this.loggedIn = true

                    const expires = expires_in / 60 / 60 / 24 // response is in seconds, we need to pass days
                    Cookies.set('access_token', access_token, {expires})
                    Cookies.set('refresh_token', refresh_token, {expires})

                    // todo complete login handle
                }catch (error) {
                    let errors = await error.response.data.errors
                    this.errors = Object.values(errors).flat()
                    this.formSubmitted = false
                }
            }
        }
    }
</script>
