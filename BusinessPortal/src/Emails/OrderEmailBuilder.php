<?php

/**
 * Builds subject + HTML body for the fabrication-order notification email.
 */
class OrderEmailBuilder
{
    /**
     * @return array{subject: string, body: string}
     */
    public static function build(array $order, array $jobs, ?array $attachment = null): array
    {
        $subject = "New Fabrication Order #{$order['id']} - {$order['customer_name']}";

        ob_start();
        // Vars consumed by the template:
        //   $order, $jobs, $attachment
        require __DIR__ . '/templates/order-notification.php';
        $body = ob_get_clean();

        return ['subject' => $subject, 'body' => $body];
    }
}
