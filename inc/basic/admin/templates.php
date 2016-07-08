<!-- template for the modal component -->
<template type="x/template" id="user-notification-modal-template">
    <div class="modal-mask" v-show="show" transition="modal">
        <div class="modal-wrapper">
            <div class="modal-container">

                <div class="modal-header">
                    <slot name="header">
                        User Notifications
                    </slot>
                </div>

                <div class="modal-body">
                    <slot name="body">
                        <table>
                            <tr v-for="( product_id, noti_object ) in admin_notification">
                                <td> <?php _e( 'New notification added for product ' );?> <a data-id="{{ product_id }}" class="wcn_admin_noti_prod_link"  href="{{ noti_object.edit_link }}">{{ noti_object.product_title }}</a> </td>
                            </tr>
                        </table>
                    </slot>
                </div>

                <div class="modal-footer">
                    <slot name="footer">
                        <button class="modal-default-button button-primary"
                        @click="show = false">
                        OK
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>