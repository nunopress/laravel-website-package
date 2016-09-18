<?php

use NunoPress\Laravel\Package\Website\Http\Controllers\WebsiteController;

// Register web routes

Route::get('/{page?}', WebsiteController::class)
    ->where('page', '[a-zA-Z0-9][a-zA-Z0-9-_/]+')
    ->defaults('page', 'index')
    ->middleware('web')
;
