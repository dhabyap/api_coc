/**
* API endpoint for AJAX event updates.
*/
public function eventsSummary()
{
return response()->json([
'events' => $this->eventService->getEventsSummary()
]);
}
}