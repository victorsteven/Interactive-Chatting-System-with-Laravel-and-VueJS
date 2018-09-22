<template>
    <div class="alert alert-flash" 
        :class="'alert-'+level"  
        role="alert" 
        v-show="show" 
        v-text="body"
        >
</div>
</template>

<script>
    export default {
        //remember, we dont override a property, so we define it below again
        props: ['message'],
        data(){
            return {
                // body: '',
                body: this.message, //THis is the body of the flash message from the server, if we need flash from the client side, where we need Vue, then, this is overwritten. because, is not just the message that is passed, both the level is also passed. what i mean is that an object is passed

                level: 'success',
                show: false,
            }
        },
       created(){
           if(this.message){
            //    this.flash(this.message); //remember, if we flash from server side, we will get this message.
               //but observe in the flash method, we are passing an object, but from here, we are using a string
               this.flash(); // now, we are flashing from the server side.
           }

           window.events.$on('flash', data => this.flash(data));
       },

       methods: {
           //note the data object is optional
           flash(data){
               if(data){
                   this.body = data.message;
                    this.level = data.level;
               }
               this.show = true;
               this.hide();
           },

            hide(){
                setTimeout(() => {
                   this.show = false
               }, 3000);
            }
       }
    }
</script>


<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
