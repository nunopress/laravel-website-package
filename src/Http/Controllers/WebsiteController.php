<?php 

namespace NunoPress\Laravel\Package\Website\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class WebsiteController
 * @package NunoPress\Laravel\Package\Website\Http\Controllers
 */
class WebsiteController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $configuration
     * @return array
     */
    private function validateRequestConfiguration(array $configuration)
    {
        $data = [];

        foreach ($configuration as $templateKey => $params) {
            if (!isset($params['method'])) {
                throw new \RuntimeException('The key {method} need to send a request.');
            }

            // todo: uri not need I think.
            if (!isset($params['uri'])) {
                $params['uri'] = '';
            }

            // Check post params
            if ('post' === strtolower($params['method']) and !isset($params['form_params'])) {
                throw new \RuntimeException('The key {form_params} need to send a POST request.');
            }

            $data[$templateKey] = $params;
        }

        return $data;
    }

    /**
     * @param string $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke($page = 'index')
    {
        if (!\View::exists(sprintf('vendor.website.%s', $page))) {
            abort(404, sprintf('Website page {%s} not found.', $page));
        }

        $requestParams = config('website.http_request_params');

        if (is_array($requestParams)) {
            $data = $this->validateRequestConfiguration($requestParams);

            // Cycle our internal data
            foreach ($data as $k => $v) {
                // Check cache first
                $minutes = config('website.http_cache_minutes', false);

                $data[$k] = \Cache::remember($k, Carbon::now()->addMinutes($minutes), function () use ($k) {
                    /** @var \Psr\Http\Message\ResponseInterface $response */
                    $response = app('website.http_client')->get($k);

                    if (200 === $response->getStatusCode()) {
                        $data = json_decode($response->getBody());
                    } else {
                        $data = [];
                    }

                    return $data;
                });
            }
        } else {
            $data = [];
        }

        return view('vendor.website.' . $page, $data);
    }
}
