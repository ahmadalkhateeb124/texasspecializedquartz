<?php

/**
 * Builds subject + HTML body for the short "installation sign-off completed"
 * notification — sent (with the sign-off PDF attached) to the customer who
 * placed the order and to the admin.
 */
class SignoffCompletedEmailBuilder
{
    /**
     * @return array{subject: string, body: string}
     */
    public static function build(array $order, array $signoff): array
    {
        $subject = "Installation Completed & Signed — Order #{$order['id']}";

        ob_start();
        // Vars consumed by the template: $order, $signoff
        require __DIR__ . '/templates/signoff-completed-notification.php';
        $body = ob_get_clean();

        return ['subject' => $subject, 'body' => $body];
    }
}
