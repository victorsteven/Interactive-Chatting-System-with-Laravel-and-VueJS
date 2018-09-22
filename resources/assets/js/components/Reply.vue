<template>
    
    <div :id="'reply-'+id" class="card"> 
        <div class="card-header" :class=" isBest ? 'bg-success' : 'bg-default'">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+ reply.owner.name"
                        v-text = "reply.owner.name">
                    </a> 
                    said  <span v-text="ago"></span>
                </h5>

                <div v-if="signedIn">
                    <favorite :reply = "reply"></favorite>
                </div>
                
            </div>
        </div>

        
        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                <div class="form-group">
                    <textarea class="form-control" name="" id="" v-model="body" required></textarea>
                </div>

                <button class="btn btn-sm btn-primary" >Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false" type="button">Cancel</button>
            </form>
                
            </div>
            <div v-else v-html = "body">
            </div>
        </div>

        <!-- @can('update', $reply) -->
        <div class="card-footer level" v-show="authorize('owns', reply) || authorize('owns', reply.thread)">
            <!-- <div v-if="canUpdate"> -->
            <!-- <div v-if="authorize('updateReply', reply)"> -->
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-sm mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-sm btn-danger mr-2" @click="destroy">Delete</button>
            </div>
            
            <button class="btn btn-sm btn-primary"   style="margin-left:auto;" @click="markAsBest" v-if="authorize('owns', reply.thread)">
                    Best Reply?
            </button>
            <!-- note: reply.thread, is the eloquent relationship reply has with thread -->
        </div>
        <!-- @endcan -->
    </div>
</template>


<script>

    import Favorite from "./Favorite.vue";
    import moment from 'moment';

    export default {
        
        //specify any child component
        components: { Favorite },

        props: ['reply'], //note data here, is the Reply eloquent instance


        data(){
            return {
                id: this.reply.id,
                editing: false,
                body: this.reply.body,
                isBest: this.reply.isBest,
                // reply: this.reply,
                // thread: window.thread, //this is to make vue track the best reply id, we need to pass the thread here
            }
        },

        computed: {

            // //this is when we are using the encoded thread
            // isBest(){
            //     return this.thread.best_reply_id == this.id;
            // },

            ago(){
                return moment(this.reply.created_at).fromNow() + '...';
            },

            //The is defined as a prototype in bootrap.js
            // signedIn(){
            //     return window.App.signedIn
            // },

            // //client side validation
            // canUpdate(){
            //     //dont forget, data here refers to "reply"
            //     //what it reads is if the id of the user that left the reply is equal to the authenticated user id, then he can update the reply
            //     // return this.reply.user_id == window.App.user.id
            //         //or we use mixins
            //     return this.authorize(user => this.reply.user_id == user.id);
            // }
        },

        created(){

            window.events.$on('best-reply-selected', id => {
                    //we are updating the isBest here, is the new best reply id equal to my id?
                this.isBest = (id === this.id)
            });
        },

        methods: {
            update(){
                //we send through the updated body
                axios.patch('/replies/' + this.id, {
                    body: this.body

                }).catch(error => {
                    flash(error.response.data.message, 'danger');
                });

                this.editing = false;

                flash('updated')
            },

            destroy(){
                axios.delete('/replies/' + this.id);

                //we grab the root element $el and fade it out. This is jquery
                // $(this.$el).fadeOut(300, () => {
                //     flash('successfully deleted reply');
                // });

                this.$emit('deleted', this.id);

            },

            markAsBest(){


                 axios.post('/replies/' + this.id + '/best');

                //every reply component can reply itself and ask, if they are the best reply
                 window.events.$emit('best-reply-selected', this.id);

                //when using thread
                // this.thread.best_reply_id = this.id;
            }

        }

    }


</script>