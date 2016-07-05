;(function($){
    $(document).ready(function(){
        // register modal component
        Vue.component('user-notification-modal', {
            template: '#user-notification-modal-template',
            props: [ 'show', 'admin_notification']
        })

        var seller_notice = new Vue({
            el: '#user-notification-app',
            data: {
                showModal: false,
                admin_notification : admin_notification
            }
        })

        var seller_admin_dashboard = {

            init : function() {
                $(document).on( 'click', '.user-notification-notice a', function() { //.notice.is-dismissible
                    seller_admin_dashboard.remove_notification($(this));
                });
            },

            //remove notification
            remove_notification : function( obj ) {
                $.post(
                    ajaxurl,
                    {
                        action : 'wcn_remove_notification'
                    },
                    function(data){
                        if( data != 'false' ) {
                            var admin_notification = JSON.parse( data );
                            $('.user-notification-notice').remove();
                            if ( obj.data('href') ) {
                                window.location = obj.data('href');
                            }
                        }


                        console.log(data);
                    }
                )
            }
        }

        seller_admin_dashboard.init();

    });
}(jQuery))