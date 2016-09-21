<?php

namespace NunoPress\Laravel\Package\Website\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

/**
 * Class WebsiteController
 *
 * @package NunoPress\Laravel\Package\Website\Http\Controllers
 */
class WebsiteController extends BaseController
{
    /**
     * @param $page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke($page)
    {
        // Validate view
        if (!\View::exists(sprintf('vendor.website.%s', $page))) {
            abort(404, sprintf('Website page {%s} not found.', $page));
        }

        $websiteData = new Collection(config('website.site', []));
        $pageData = new Collection(config(sprintf('website.pages.%s', $page), []));

        $data = [
            'site' => $websiteData,
            'page' => $pageData
        ];

        return view('vendor.website.' . $page, $data);
    }
}
