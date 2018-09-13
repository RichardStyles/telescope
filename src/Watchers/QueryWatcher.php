<?php

namespace Laravel\Telescope\Watchers;

use Laravel\Telescope\Telescope;
use Laravel\Telescope\IncomingEntry;
use Illuminate\Database\Events\QueryExecuted;

class QueryWatcher extends Watcher
{
    /**
     * Register the watcher.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function register($app)
    {
        $app['events']->listen(QueryExecuted::class, [$this, 'recordQuery']);
    }

    /**
     * Record a query was executed.
     *
     * @param \Illuminate\Database\Events\QueryExecuted  $event
     * @return void
     */
    public function recordQuery(QueryExecuted $event)
    {
        Telescope::recordQuery(IncomingEntry::make([
            'connection' => $event->connectionName,
            'bindings' => $event->bindings,
            'sql' => $event->sql,
            'time' => number_format($event->time, 2),
        ]));
    }
}