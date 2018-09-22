<template>
    
    <ul class="pagination mt-4 mx-2" v-show="shouldPaginate">
    <li class="page-item" v-show="prevUrl">
      <a class="page-link" href="#" aria-label="Previous" rel="prev" @click.prevent="page--">
        <span aria-hidden="true">&laquo; Previous</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <li class="page-item" v-show="nextUrl">
      <a class="page-link" href="#" aria-label="Next" rel="next" @click.prevent="page++">
        <span aria-hidden="true">Next &raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>

</template>


<script>
    
    export default {

        props: ['dataSet'],

        data(){
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false,
            }
        },

        watch: {
            //we are going to keep an eye on the dataSet property like this:
            dataSet(){

                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },

            //keep an eye on the page property, if it changes
            page(){
                this.broadcast().updateUrl();
            }
        },

        computed: {
            shouldPaginate(){
                // return this.preUrl || this.nextUrl; //this is returning a string, we want it to return a boolean
                return !! this.prevUrl || !! this.nextUrl; //so we do this
            }
        },

        methods: {
            broadcast(){
                return this.$emit('changed', this.page);

            },

            updateUrl(){
                //the page is going to be the same but we are updating the query string
                history.pushState(null, null, '?page=' + this.page);
            }
        }
    }
</script>