<?php

namespace App\Scheduler;

use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Scheduler\Attribute\AsScheduledTask;
use App\Message\RemindReservationsMessage;

#[AsSchedule('Reminder')]
final class ReminderSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): Schedule
    {
        $dateinterval = \DateInterval::createFromDateString('1 hour');

        return (new Schedule())
            ->add(
                RecurringMessage::every(
                    '5 minutes',
                    new RemindReservationsMessage($dateinterval)
                ),
            )
            ->stateful($this->cache)
            ->processOnlyLastMissedRun(true)
        ;
    }
}
