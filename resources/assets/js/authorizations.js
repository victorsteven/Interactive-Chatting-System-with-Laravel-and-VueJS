//this is authorization on the client side, do note that we are doing a two way authoriztion, in the client side and in the server side

let user = window.App.user //the authenticated user

module.exports = {
    // updateReply(reply) {
    //     return reply.user_id === user.id;
    // },
    // updateThread(thread) {
    //     return thread.user_id === user.id;
    // },

    owns(model, props = 'user_id'){
        return model[props] === user.id;
    },

    isAdmin(){
        //check if the signed in user name is JohnDoe or JaneDoe, if any, return them as admin
        return ['JohnDoe', 'JaneDoe'].includes(user.name);
    }
};