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
                            <?php
                            //foreach( $admin_notification['notification_strings'] as $key => $each_notification ) {
                                ?>
                                <tr v-for="( key, each_notification ) in admin_notification.notification_strings">
                                    <td>{{ each_notification }}</td>
                                </tr>
                            <?php
                            //}
                            ?>
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