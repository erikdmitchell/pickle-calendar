<?php
/**
 * Event details template.
 *
 * @package PickleCalendar
 * @since   1.0.0
 */

$event_details = pc_get_event_details();
?>

<div class="event-details">
    <?php foreach ( $event_details as $event_dates ) : ?>
        <div class="event-dates">
            <div class="start-date">
                <?php echo $event_dates['start_date']; // phpcs:ignore ?>
            </div>

            <div class="end-date">
                <?php echo $event_dates['end_date']; // phpcs:ignore ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
