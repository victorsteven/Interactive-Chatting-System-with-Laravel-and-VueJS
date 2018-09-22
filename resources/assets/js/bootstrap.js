
window._ = require('lodash');
window.Popper = require('popper.js').default;


window.Vue = require('vue');
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// //this is the authorize mixins
// //when authorize method triggers the handler, it is sent through the authenticated user
// Vue.prototype.authorize = function(handler){
//     //additional admin privileges

//     //return the authenticated user
//     let user = window.App.user;

//     // if(! user) return false;
//     // return handler(user);

//     return user ? handler(user) : false;
// }


//this is the authorize mixins
//when authorize method triggers the handler, it is sent through the authenticated user

let authorizations = require('./authorizations.js');

Vue.prototype.authorize = function(...params){
    //additional admin privileges

    //if the authenticated user is not signed in, they are not authorized
    if(! window.App.signedIn) return false;

    //if the first is a string, they are using a named authorization:
    if(typeof params[0] === "string") {

        return authorizations[params[0]](params[1]);
    }
    //So now, when we call authorize, we will check whether u are giving us 
    // authorize('foo', 'bar') this format or
    // authorize(() => {}) this format

    return params[0](window.App.user);

}

Vue.prototype.signedIn = window.App.signedIn;

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


window.events = new Vue();

window.flash = function (message, level = "success"){
    window.events.$emit('flash', {message, level});
} //flash('my new message')
