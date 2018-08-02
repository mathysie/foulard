<?php

namespace foulard\google;

use Google_Client;
use Google_Service_Calendar;

class CalendarHelper
{
    /** @var Google_Client */
    protected $client;

    /** @var Google_Service_Calendar */
    protected $service;

    public function __construct()
    {
        $this->client = $this->getClient();
        $this->service = $this->getService();
    }

    public function getEvents()
    {
        // Print the next 10 events on the user's calendar.
        $calendarId = 'primary';
        $optParams = [
          'maxResults' => 10,
          'orderBy' => 'startTime',
          'singleEvents' => true,
          'timeMin' => date('c'),
        ];
        $results = $this->service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        if (empty($events)) {
            echo "No upcoming events found.\n";
        } else {
            echo "Upcoming events:\n";
            foreach ($events as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                printf("%s (%s)\n", $event->getSummary(), $start);
            }
        }
    }

    protected function getService(): Google_Service_Calendar
    {
        return new Google_Service_Calendar($this->client);
    }

    protected function getClient(): Google_Client
    {
        $client = new Google_Client();
        $client->setApplicationName('Foulard');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig(__DIR__ . '/credentials.json');
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = 'foulard/google/token.json';
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }
}
