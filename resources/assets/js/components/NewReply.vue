<template>
    <div>
        <div class="card p-2" v-if="signedIn">
            <textarea 
            name="body" 
            id="body" 
            class="form-control mt-2" 
            placeholder="Add reply.." 
            v-model="body"
            required>
            </textarea>

            <button 
            type="submit" 
            class="btn btn-primary mt-4 mb-2 ml-2" 
            @click="addReply">Add Reply</button>
        </div>

        <p class="text-center mt-4" v-else>
            Please <a href="/login">sign in</a>  to participate in this discussion
        </p>
    </div>



</template>

<script>

import 'at.js';
import 'jquery.caret';

    
    export default {
        data(){
            return {
                body: '',
                
            }
        },

        mounted(){
            $('#body').atwho({
            at: "@",
            delay: 750,
            //data:"http://localhost:9000/users.php"

            callbacks: {
                /*
                 If function is given, At.js will invoke it if local filter can not find any data
                 @param query [String] matched query
                 @param callback [Function] callback to render page.
                */
                remoteFilter: function(query, callback) {
                    // console.log('called');

                //   console.log(query);

                // the "query" here is what u have typed in 
                  $.getJSON("/api/users", {name: query},

                  function(username) {
                    callback(username)
                  });
                }
              }
        });
        },

        //now defined in bootstrap.js
        // computed: {
        //     signedIn(){
        //         return window.App.signedIn;
        //     }
        // },

        methods: {
            addReply(){

                axios.post(location.pathname + "/replies", {body: this.body})

                .catch(error => {
                    // console.log('ERROR');
                    // console.log(error.response);
                    flash(error.response.data, 'danger');
                    
                })
                    .then(({data}) => {
                        this.body = '',
                        flash('Your reply has been posted');

                        this.$emit('created', data);
                    });
            }
        }
    }
</script>
