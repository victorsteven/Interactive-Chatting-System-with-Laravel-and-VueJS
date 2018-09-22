<template>
    <input type="file"  id="" accept="image/*" @change="onChange">
</template>


<script>
    
    export default {

        methods: {
            onChange(e){
                // console.log(e.target.files.File.name);
                // console.log(e.target.files[0]);
                if(! e.target.files.length) return;

                let file = e.target.files[0]; //the first file

                let reader = new FileReader();

                reader.readAsDataURL(file);

                //since it will take some time to load
                //so, we trigger a function once it is loaded

                reader.onload = e => {

                    let src = e.target.result; //this is the data url to the image
                    // note: it is the "file" that is submitted to the server
                    this.$emit('loaded', {src, file}); //we are using short hand here.

                    // // it would have been:
                    // this.$emit('loaded', {
                    //     src: e.target.result,
                    //     file: file
                    // });


                    // this.file = e.target.result;
                     //this is the data URL, note, not the image itself
                    //because we have updated the avatar, it will re-evaluate in the image tag

                    //now we want to persist to the server

                }
            }
        }
    }
</script>
