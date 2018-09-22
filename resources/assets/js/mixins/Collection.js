//This is a mixin in Vue, it can be likened to a trait in laravel

export default {

    data(){
        return {
            items: []
        }
    },
    
    methods: {
        add(item){
                //remember, items here means replies
                this.items.push(item);
                this.$emit('added')
            },
        remove(index){
            this.items.splice(index, 1); //remove that reply, exactly one from the items
            // this.$emit('removed') 
            // flash('reply was deleted');
        }, 
    }
}