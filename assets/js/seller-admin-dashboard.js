;(function($){
    $(document).ready(function(){
        // register modal component
        Vue.component('user-notification-modal', {
            template: '#user-notification-modal-template',
            props: [ 'show', 'admin_notification']
        });

        var seller_notice = new Vue({
            el: '#user-notification-app',
            data: {
                showModal: false,
                admin_notification : admin_notification,
                admin_noti_length : admin_noti_length
            }
        });

        var seller_admin_dashboard = {

            init : function() {
                $(document).on( 'click', 'a.wcn_admin_noti_prod_link', function() {
                    seller_admin_dashboard.remove_notification($(this));
                });
            },

            //remove notification
            remove_notification : function( obj ) {
                $.post(
                    ajaxurl,
                    {
                        action : 'wcn_remove_admin_notification',
                        product_id : obj.data('id')
                    },
                    function(data){
                    }
                );
            }
        };

        seller_admin_dashboard.init();

    });
}(jQuery))