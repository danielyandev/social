<template>
    <div class="card">
        <div class="card-body">
            <form @submit.prevent="publish">
                <textarea v-model="form.text" class="form-control" rows="5" placeholder="Share your awesome thoughts :)"></textarea>

                <div class="mt-3">
                    <span class="text-primary">Visible for: </span>
                    <b-form-radio-group
                        v-model="form.is_public"
                        :options="options"
                        class="d-inline"
                        value-field="is_public"
                        text-field="label"
                    ></b-form-radio-group>
                    <button type="submit" class="btn btn-primary mt-2 float-right">Publish</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FeedTextarea",
        props: ['user'],
        data() {
            return {
                form: {
                    text: '',
                    is_public: true
                },
                options: [
                    { is_public: false, label: 'Friends' },
                    { is_public: true, label: 'Public' }
                ]
            }
        },
        methods: {
            publish: async function () {
                const url = '/users/' + this.user.id + '/posts'
                try{
                    const {data} = await axios.post(url, this.form)
                    this.form.text = ''
                    this.$parent.add_post(data.data)
                }catch (e) {
                    // todo handle error
                }
            }
        }
    }
</script>
