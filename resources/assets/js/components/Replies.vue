<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <!-- the parent is listening when the child is being deleted -->
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <!--we are binding a prop on the paginator to the value of the "dataSet" we received from our ajax call -->
        <paginator :dataSet="dataSet" @changed="fetch"></paginator> 

        <!-- <new-reply :endpoint="endpoint"  @created="add"></new-reply> -->
        <!-- get the locked attribute from the parent which is thread. if is not locked, show new reply form -->
        <hr>
        <p v-if="$parent.locked" class="text-center">
            This thread has been locked, no further replies allowed
        </p>

        <new-reply @created="add" v-else></new-reply>


        
    </div>

</template>

<script>

    import Reply from "./Reply.vue";
    import NewReply from "./NewReply.vue";
    import collection from "../mixins/Collection";


    export default {

        components: {Reply, NewReply},

        mixins: [collection],


        data(){
            return {
                dataSet: false,
                // items: [], //beacause items is now stored in our mixin called "Collection", we dont need to still define this array here

            }
        },

        created(){
            this.fetch();
        },

        methods: {

            fetch(page){
                axios.get(this.url(page)) //fetch the url, when u are done, u will refresh the content
                    .then(this.refresh)
            },

            url(page){
                //if u did not give us a page, lets see if we can get it from the query string
                if(! page){
                    let query = location.search.match(/page=(\d+)/);
                //did we match anything, if yes, get the first item, if no, start from one

                page = query ? query[1] : 1;

                }

                return `${location.pathname}/replies?page=${page}`;
            },

            // refresh(response){
            //     console.log(response);    
            // },
            //instead of the above, we are going to use destructuring to filter down to the data
            refresh({data}){
                // console.log(data);
                this.dataSet = data; //check in the console log, u will see a big data object, we are saving this in "dataSet"
                this.items = data.data; //inside the big data object, we also have another object called data inside it, thats why we did data.data, note, we are saving it in the items array  
                window.scrollTo(0,0);
            },

            //These two methods are thrown into the mixin called Collection.js
            // add(reply){
            //     //remember, items here means replies
            //     this.items.push(reply);
            //     this.$emit('added')
            // },
            // remove(index){
            //     this.items.splice(index, 1); //remove that reply, exactly one from the items
            //     this.$emit('removed') 
            //     flash('reply was deleted');
            // }, 
        }
    }
</script>