;(function($){
    $(document).ready(function(){
        var product_noti = new Vue({
            el : '#wn-product-notification-app',
            data : {
                is_available  : is_available,
                is_discount : is_discount
            },
            methods : {
                set_notification : function( product_id, notification_for , notification_type, action_type ) {
                    $.post(
                        wcn_data.ajaxurl,
                        {
                            action : wcn_data.action,
                            product_id : product_id,
                            notification_for : notification_for,
                            notification_type : notification_type,
                            action_type : action_type
                        },
                        function( data ) {
                            var data = JSON.parse(data);
                            console.log(data);
                            if( data.notification_for ) {
                                switch( data.notification_for ) {
                                    case 'availablity' :
                                        product_noti.is_available = !product_noti.is_available;
                                        break;
                                    case 'discount' :
                                        product_noti.is_discount = !product_noti.is_discount;
                                        break;
                                }
                            }

                        }
                    )
                },

                test_notification : function() {
                    alert('aaa');
                }
            }
        })

    });
}(jQuery))