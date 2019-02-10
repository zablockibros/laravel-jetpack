<?php

namespace ZablockiBros\Jetpack;

use Composer\Script\Event;

class ComposerScripts
{
    /**
     * Handle the post-autoload-dump Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postAutoloadDump(Event $event)
    {
        print_r("HAPPENED" . PHP_EOL);
    }
}
