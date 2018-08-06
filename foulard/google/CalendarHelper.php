<?php

namespace foulard\google;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Calendar;
use mako\config\Config;

class CalendarHelper
{
    /** @var Google_Client */
    protected $client;

    /** @var Google_Service_Calendar */
    protected $service;

    /** @var string */
    protected $calendar_id;

    /** @var Config */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;

        $this->client = $this->createClient();
        $this->service = new Google_Service_Calendar($this->client);
        $this->calendar_id = $this->config->get('calendar.calendarID');
    }

    public function getEvents(): array
    {
        // Print the next 10 events on the user's calendar.
        $optParams = [
          'maxResults'   => 20,
          'orderBy'      => 'startTime',
          'singleEvents' => true,
          'timeMin'      => date('c'),
        ];
        $results = $this->service->events->listEvents($this->calendar_id, $optParams);
        $events = $results->getItems();

        return $events ?? [];
    }

    public function getService(): Google_Service_Calendar
    {
        return $this->service;
    }

    public function getClient(): Google_Client
    {
        return $this->client;
    }

    public function getCalendar(): Google_Service_Calendar_Calendar
    {
        return $this->service->calendars->get($this->calendar_id);
    }

    protected function createClient(): Google_Client
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
