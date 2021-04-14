<?php

/*
 * This file is part of the eluceo/iCal package.
 *
 * (c) 2021 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Eluceo\iCal\Domain\Entity;

use Eluceo\iCal\Domain\Collection\Events;
use Eluceo\iCal\Domain\Collection\EventsArray;
use Eluceo\iCal\Domain\Collection\EventsGenerator;
use InvalidArgumentException;
use Iterator;

class Calendar
{
    private string $productIdentifier = '-//eluceo/ical//2.0/EN';

    private Events $events;

    protected array $attributes = [];

    /**
     * @var array<TimeZone>
     */
    private array $timeZones = [];

    /**
     * @param Event[]|Iterator<Event>|Events $events
     */
    public function __construct($events = [])
    {
        $this->events = $this->ensureEventsObject($events);
    }

    /**
     * @param Event[]|Iterator<Event>|Events $events
     */
    private function ensureEventsObject($events = []): Events
    {
        if ($events instanceof Events) {
            return $events;
        }

        if (is_array($events)) {
            return new EventsArray($events);
        }

        if ($events instanceof Iterator) {
            return new EventsGenerator($events);
        }

        throw new InvalidArgumentException('$events must be an array, an object implementing Iterator or an instance of Events.');
    }

    public function getProductIdentifier(): string
    {
        return $this->productIdentifier;
    }

    public function setProductIdentifier(string $productIdentifier): self
    {
        $this->productIdentifier = $productIdentifier;

        return $this;
    }

    public function getEvents(): Events
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        $this->events->addEvent($event);

        return $this;
    }

    /**
     * @return array<TimeZone>
     */
    public function getTimeZones(): array
    {
        return $this->timeZones;
    }

    public function addTimeZone(TimeZone $timeZone): self
    {
        $this->timeZones[] = $timeZone;

        return $this;
    }


    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value)
    {
        $this->setAttribute($name, $value);
    }

    public function __isset($name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function __unset($name)
    {
        unset($this->attributes[$name]);
    }


    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttribute(string $name, mixed $value)
    {
        $this->attributes[$name] = $value;
    }
}
