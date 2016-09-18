<?php

namespace NunoPress\Laravel\Package\Website\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use NunoPress\Laravel\Package\Website\ApiCollection;

/**
 * Class WebsiteController
 *
 * @package NunoPress\Laravel\Package\Website\Http\Controllers
 */
class WebsiteController extends BaseController
{
    /**
     * @param string $page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke($page = 'index')
    {
        // Validate view
        // todo: Not need to change the slash with dots, laravel done this.
        if (!\View::exists(sprintf('vendor.website.%s', $page))) {
            abort(404, sprintf('Website page {%s} not found.', $page));
        }

        $requestParams = config('website.http_request_params');

        $data = new ApiCollection($requestParams);

        return view('vendor.website.' . $page, $data->all());
    }
}
