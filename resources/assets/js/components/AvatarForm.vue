<template>
    <div>
        <div class="level">
            <img :src="avatar" :alt="user.name" width="50" height="50" class="mr-2">
            
            <h1 v-text="user.name"></h1>

        </div>
        

        <form v-if="canUpdate"  method="POST" enctype="multipart/form-data">

            <image-upload name="foobar" @loaded ="onLoad"></image-upload>
        
            <!-- <button type="submit" class="btn btn-primary">Add Avatar</button> -->
        </form>   


        <!-- <img src="{{ asset('storage/' . $profileUser->avatar()) }}" alt="{{ $profileUser->name }}" width="50" height="50"> -->

         
    </div>
</template>

<script>

    import ImageUpload from './ImageUpload.vue';

    export default {

        props: ['user'],

        components: { ImageUpload },

        data(){
            return {
                avatar: "/" + this.user.avatar_path,
            }
        },

        computed: {

            canUpdate(){
                //the signed in user, is their id equal to the profile user?
                return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onLoad(avatar){
                //lets update the avatar path
                this.avatar = avatar.src;

                
                //now we want to persist to the server
                this.persist(avatar.file); //when we call the method, we didnt send through the data URL but the file object itself
            },

            persist(avatar){
                let data = new FormData(); //this guy is needed because we are using "multipart" in our form
                //append it and put the form-field
                //we made the avatar name equal to the file object
                data.append('avatar', avatar);

                //now we post that form data to the server
                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded'));
            }
        }
        
    }
</script>
