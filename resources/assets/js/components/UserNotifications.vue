<template>
<div>
    
    <li class="nav-item dropdown" v-if="notifications.length">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-bell"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <ul class="">
            <li v-for="(notification, index) in notifications" :key="index">
                <a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)"></a>
            </li>
        </ul>
                
        </div>
    </li>
    </div>
</template>

<script>
    
    export default {

        data(){
            return {
                notifications: false,
            }
        },

        created(){
            axios.get('/profiles/' + window.App.user.name + '/notifications')
            .then(response => this.notifications = response.data);
        }, 

        methods: {

            markAsRead(notification){
                // "/profiles/{$userName}/notifications/{$notificationId}"
                axios.delete("/profiles/" + window.App.user.name + "/notifications/" + notification.id);
            }
        }

    }
</script>
