<template>
    
    <button :class="classes"  @click="subscribe">Subscribe</button>

</template>

<script>
    
    export default {

        props: ['active'],

        data(){
            return {
                acti: this.active
            }
        },

        computed: {
            classes(){
                return ['btn', this.acti ? 'btn-primary' : 'btn-default'];
            }
        },
        
        methods: {
            subscribe(){

                axios[this.acti ? 'delete' : 'post'](location.pathname + '/subscriptions');

                //now since active could be a post or a delete request, make it the inverse of what it was before
                this.acti = ! this.acti;
           
               

                // flash('Subscribed');
            }
        }
    }
</script>
