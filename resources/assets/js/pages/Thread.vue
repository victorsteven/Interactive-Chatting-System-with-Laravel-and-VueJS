<script>
    
    import Replies from "../components/Replies";
    import SubscribeButton from "../components/SubscribeButton";

    export default {

        components: {Replies, SubscribeButton},

        // props: ['dataRepliesCount', 'dataLocked'],
        props: ['thread'],

        data(){

            return {
                // repliesCount: this.dataRepliesCount,
                // locked: this.dataLocked,
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                slug: this.thread.slug,
                editing: false,

                title: this.thread.title,
                body: this.thread.body,
                form: {
                  title: this.thread.title,
                    body: this.thread.body  
                },
                // form: {},


                // locked: false //this guy will not make our changes on the client side persist on the server side, to make it persist, we need to assign this value from what we got from the server side
            }
        },
        //this is when we didnt pass the propeties in the form object in "data" above
        // created(){
        //     this.resetForm()
        // },

        computed: {

            endpoint(){
                return "/locked-threads/" + this.slug;
            }
        },

        methods: {

            // cancel(){
            //     // this.editing = false;

            //     // this.form.title = this.thread.title;
            //     // this.form.body = this.thread.body;

            // },

            // toggleLock(){

            //     this.locked ? this.unlock() : this.lock()

            // },
            // unlock(){
            //     axios.delete(this.endpoint);
            //     this.locked = false;
            // },

            // lock(){
            //     axios.post(this.endpoint)
            //     this.locked = true;
            // }

            toggleLock(){

                axios[this.locked ? 'delete' : 'post'](this.endpoint)

                this.locked = ! this.locked;
            },

            updateThread(){

                let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;
                // axios.patch(uri, {
                //     title: this.form.title,
                //     body: this.form.body
                // }
                //OR, since we have those two in the form object already
                axios.patch(uri, this.form)
                        .then((data) => {
                         this.editing = false;
                         console.log(data); 
                         //now set the values to what the user typed in
                         this.title = this.form.title;
                         this.body = this.form.body;

                         flash('Successfully updated');

                     });       
                     
            },

            resetForm(){
                //when u click cancel, that thing that was in the form before, just give us back
                this.form = {
                    // title: this.thread.title,
                    // body: this.thread.body
                    title: this.title,
                    body: this.body
                },
                    this.editing = false;

            }
        }
    }
</script>