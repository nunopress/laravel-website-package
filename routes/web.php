<?php

use NunoPress\Laravel\Package\Website\Http\Controllers\WebsiteController;

// Register web routes

Route::get('/{page?}', WebsiteController::class)->middleware('web');