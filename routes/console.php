<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('task-reminder:cron')->everyMinute();
