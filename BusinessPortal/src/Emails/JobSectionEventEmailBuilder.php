<?php

/**
 * Builds subject + HTML body for the "your job section has a schedule update" email
 * sent to the customer who placed the order, whenever their assigned employee
 * creates or updates an event for one of the order's job sections.
 */
class JobSectionEventEmailBuilder
{
    /**
     * @return array{subject: string, body: string}
     */
    public static function build(array $order, array $job, array $event): array
    {
        $eventLabel = JobScheduleRepository::EVENT_TYPES[$event['event_type']] ?? $event['event_type'];
        $subject    = "Update on Order #{$order['id']} — {$eventLabel}";

        ob_start();
        // Vars consumed by the template: $order, $job, $event
        require __DIR__ . '/templates/job-section-event-update.php';
        $body = ob_get_clean();

        return ['subject' => $subject, 'body' => $body];
    }
}
