<?php
require 'GoogleAPI-Vendor/autoload.php';

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\EventAttendee;
use Google\Service\Calendar\EventReminder;

class CalendarInvitation
{
    private $client;
    private $calendarService;

    public function __construct($credentialsPath, $tokenPath)
    {
        $this->client = new Client();
        $this->client->setApplicationName('Google Calendar API PHP Quickstart');
        $this->client->setScopes(Calendar::CALENDAR);
        $this->client->setAuthConfig($credentialsPath);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired, get a new one.
        if ($this->client->isAccessTokenExpired()) {
            // Refresh the token if possible, otherwise fetch a new one.
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $this->client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
                $this->client->setAccessToken($accessToken);

                // Save the token to a file.
                if (!file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
            }
        }

        $this->calendarService = new Calendar($this->client);
    }

    public function createEvent($data)
    {
        $event = new Event([
            'summary' => $data['summary'],
            'location' => $data['location'],
            'description' => $data['description'],
            'start' => new EventDateTime([
                'dateTime' => $data['start']['dateTime'],
                'timeZone' => $data['start']['timeZone']
            ]),
            'end' => new EventDateTime([
                'dateTime' => $data['end']['dateTime'],
                'timeZone' => $data['end']['timeZone']
            ]),
            'attendees' => array_map(function ($attendee) {
                return new EventAttendee(['email' => $attendee['email']]);
            }, $data['attendees']),
            'reminders' => new EventReminder([
                'useDefault' => $data['reminders']['useDefault'],
                'overrides' => array_map(function ($override) {
                    return new EventReminder(['method' => $override['method'], 'minutes' => $override['minutes']]);
                }, $data['reminders']['overrides'])
            ])
        ]);

        $calendarId = 'primary';
        $event = $this->calendarService->events->insert($calendarId, $event);
        printf('Event created: %s\n', $event->htmlLink);
    }
}

// Datos de ejemplo
$data = [
    "summary" => "1:1 Business Craft",
    "location" => "Google Meet: Please follow the google meet url",
    "description" => "Business teammates Q2Q@/&$$/()(&ñ",
    "colorId" => 2,
    "start" => ["dateTime" => "2024-06-29T11:00:00+05:30", "timeZone" => "America/Bogota"],
    "end" => ["dateTime" => "2024-06-30T12:00:00+05:30", "timeZone" => "America/Bogota"],
    "attendees" => [
        ["email" => "camilo.casadiego@trotalo.com"],
        ["email" => "yimsanabria@gmail.com"],
        ["email" => "lyah.smo@gmail.com", "organizer" => true]
    ],
    "reminders" => [
        "useDefault" => false,
        "overrides" => [
            ["method" => "email", "minutes" => 10],
            ["method" => "popup", "minutes" => 10]
        ]
    ],
    "creator" => [
        "email" => "lyah.smo@gmail.com",
        "self" => true
    ],
    "organizer" => [
        "email" => "lyah.smo@gmail.com",
        "self" => true
    ]
];

$credentialsPath = 'secrets/credentials.json';
$tokenPath = 'secrets/token.json';

$invitation = new CalendarInvitation($credentialsPath, $tokenPath);
$invitation->createEvent($data);
?>