;(function($){
    $(document).ready(function(){
        wcn_admin_product_vue = new Vue({
            el: '#wpwrap',
            data : {
                primary_notification : primary_notifications,
                notifications : notifications,
                is_in_stock : is_in_stock,
                changed_data : {}, // { 'row_id' : { 'field_name' : value  } },// { 'action_type' : { 'field_name' : { 'id' : { 'id_value' : 'field_value' } }  } }
                deleted_data : [] // [ 'row_id' ]
            },

            methods : {
                changeStatus : function( element, status, event ) {
                    element.status = status;
                    console.log(element.id);
                    Vue.set(
                        this.changed_data,
                        element.id,
                        { 'status' : element.status}
                    );
                }
            }

        });

        var wcn_admin_product = {

            init : function() {

                $(document).on('click', '.wcn_tabs .wcn_tab_data a', function(e) {
                    $(this).addClass('wcn_active_tab').siblings().removeClass('wcn_active_tab')
                    $($(this).attr('href')).addClass('wcn_active').siblings().removeClass('wcn_active');
                    return false;
                });

            }
        };

        wcn_admin_product.init();
    })
}(jQuery));