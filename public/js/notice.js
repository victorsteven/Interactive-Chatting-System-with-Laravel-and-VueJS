Vue.component('greeting', {
    template: `<p>Hello i am {{name}} <button @click="change">Change Name</button> </p>`,
  
    data(){
      //note a fresh object is returned each time, so that everyone that references this data have unique values
      return {
        name: "Sam"
      }
    },
  
    methods: {
      change(){
        this.name = "Mike";
      }
    }
  });

  new Vue({
    el: "#vue1",
  
  })