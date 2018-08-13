<?php

declare(strict_types=1);

namespace foulard\google;

use foulard\calendar\CalendarParser;
use foulard\calendar\events\AanvraagEvent;
use foulard\datetime\FoulardDateTime;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Exception;
use mako\config\Config;
use mako\http\exceptions\RequestException;

class CalendarHelper
{
    /** @var Google_Client */
    protected $client;

    /** @var Google_Service_Calendar */
    protected $service;

    /** @var string */
    protected $calendar_id;

    /** @var CalendarParser */
    protected $calendarParser;

    /** @var Config */
    protected $config;

    /** @var array */
    protected $optParams = [
          'orderBy'      => 'startTime',
          'singleEvents' => true,
    ];

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->calendarParser = new CalendarParser();

        $this->client = $this->createClient();
        $this->service = new Google_Service_Calendar($this->client);
        $this->calendar_id = $this->config->get('calendar.calendarID');
    }

    public function getAanvraagEvent(string $id): AanvraagEvent
    {
        $event = $this->getEvent($id);
        $aanvraag = $this->calendarParser->parseEvent($event);

        if (is_a($aanvraag, AanvraagEvent::class)) {
            return $aanvraag;
        } else {
            throw new RequestException(400, 'Event is geen AanvraagEvent');
        }
    }

    public function getEvents(
        FoulardDateTime $start,
        FoulardDateTime $end,
        array $params = [],
        string $event_class = ''
    ): array {
        // Print the next 10 events on the user's calendar.
        $this->optParams = array_merge(
            $this->optParams,
            [
                'timeMin' => $start->formatGoogle(),
                'timeMax' => $end->formatGoogle(),
            ],
            $params
        );
        $results = $this->service->events->listEvents(
            $this->calendar_id,
            $this->optParams
        );
        $events = $results->getItems();

        return $this->calendarParser->parseEvents(
            $events ?? [],
            $event_class
        );
    }

    protected function getEvent(string $id): Google_Service_Calendar_Event
    {
        try {
            $event = $this->service->events->get($this->calendar_id, $id);
        } catch (Google_Service_Exception $e) {
            throw new RequestException(
                $e->getCode(),
                $e->getMessage(),
                $e->getPrevious()
            );
        }

        return $event;
    }

    protected function createClient(): Google_Client
    {
        $client = new Google_Client();
        $client->setApplicationName('Foulard');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig('foulard/google/credentials.json');
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = 'foulard/google/token.json';
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            throw new GoogleAuthenticationException();
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents(
                $credentialsPath,
                json_encode($client->getAccessToken())
            );
        }

        return $client;
    }
}
