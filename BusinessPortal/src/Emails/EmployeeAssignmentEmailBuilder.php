<?php

/**
 * Builds subject + HTML body for the "you've been assigned to an order" email sent to an employee.
 */
class EmployeeAssignmentEmailBuilder
{
    /**
     * @return array{subject: string, body: string}
     */
    public static function build(array $employee, array $order): array
    {
        $subject = "You've been assigned to Order #{$order['id']}";

        ob_start();
        // Vars consumed by the template: $employee, $order
        require __DIR__ . '/templates/employee-assignment-notification.php';
        $body = ob_get_clean();

        return ['subject' => $subject, 'body' => $body];
    }
}
