<?php

namespace App\Listeners;

use App\Events\AppInstalled;

class AppInstalledListener
{

    public function __construct()
    {

    }

    public function handle(AppInstalled $event) {
    }
}
