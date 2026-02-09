<?php

require __DIR__ . '/vendor/autoload.php';

use App\Services\CocEventService;
use Carbon\Carbon;

$service = new CocEventService();
$events = $service->getEventsSummary();

echo "Current Time (UTC): " . Carbon::now('UTC')->toDateTimeString() . "\n\n";
echo "Events Summary:\n";
foreach ($events as $event) {
    echo "----------------------------------------\n";
    echo "Event: " . $event['name'] . "\n";
    echo "Status: " . $event['status'] . "\n";
    echo "Countdown: " . $event['countdown'] . "\n";
}
echo "----------------------------------------\n";
