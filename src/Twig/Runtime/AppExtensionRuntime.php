<?php

namespace App\Twig\Runtime;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function diffForHumans(\DateTimeInterface $date): string
    {
        return Carbon::instance($date)->diffForHumans([
            'parts' => 2,
            'short' => false,
            'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
        ]);
    }
}
