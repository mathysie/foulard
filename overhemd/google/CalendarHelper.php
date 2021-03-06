<?php

declare(strict_types=1);

namespace overhemd\google;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Exception;
use mako\config\Config;
use mako\http\exceptions\RequestException;
use overhemd\calendar\CalendarParser;
use overhemd\calendar\events\AanvraagEvent;
use overhemd\datetime\OverhemdDateTime;

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

    public function __construct(Config $config, CalendarParser $calendarParser)
    {
        $this->config = $config;
        $this->calendarParser = $calendarParser;

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
        OverhemdDateTime $start,
        OverhemdDateTime $end,
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

    public function deleteEvent(string $id): void
    {
        $this->service->events->delete(
            $this->calendar_id,
            $id
        );
    }

    public function insertEvent(Google_Service_Calendar_Event $event): void
    {
        $this->service->events->insert(
            $this->calendar_id,
            $event
        );
    }

    public function updateEvent(Google_Service_Calendar_Event $event): void
    {
        $this->service->events->update(
            $this->calendar_id,
            $event->id,
            $event
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
        $client->setApplicationName('Overhemd');
        $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);

        $credentialsPath = 'overhemd/google/credentials.json';
        if (file_exists($credentialsPath)) {
            $client->setAuthConfig($credentialsPath);
        } else {
            throw new GoogleAuthenticationException();
        }
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $tokenPath = 'overhemd/google/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
        } else {
            throw new GoogleAuthenticationException();
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents(
                $tokenPath,
                json_encode($client->getAccessToken())
            );
        }

        return $client;
    }
}
