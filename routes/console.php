<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─── Scheduled: send donation reminders daily ─────────────────
// Runs every day at 8am — notifies accepted donors for campaigns tomorrow
Schedule::command('donations:send-reminders')->dailyAt('08:00');
