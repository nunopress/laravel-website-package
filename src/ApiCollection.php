<?php

namespace NunoPress\Laravel\Package\Website;

use Carbon\Carbon;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ApiCollection
 *
 * @package NunoPress\Laravel\Package\Website
 */
class ApiCollection extends Collection
{
    /**
     * ApiCollection constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $data = $this->validateRequestConfiguration($configuration);

        $items = $this->prepareDataForView($data);

        parent::__construct($items);
    }

    /**
     * @param array $configuration
     *
     * @return array
     */
    private function validateRequestConfiguration(array $configuration)
    {
        $data = [];

        foreach ($configuration as $templateKey => $options) {
            if (!isset($options['method'])) {
                throw new \RuntimeException(
                    'The key {method} need to send a request.'
                );
            }

            // todo: uri not need I think.
            if (!isset($options['uri'])) {
                $options['uri'] = '';
            }

            // Check params
            if (!isset($options['params'])) {
                $options['params'] = [];
            }

            $data[$templateKey] = $options;
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function prepareDataForView(array $data)
    {
        $result = [];

        // Cycle our internal data
        foreach ($data as $templateKey => $options) {
            // Check cache first
            $minutes = config('website.http_cache_minutes', false);

            $result[$templateKey] = \Cache::remember(
                $templateKey,
                Carbon::now()->addMinutes($minutes),
                function () use ($templateKey, $options) {
                    /** @var ClientInterface $client */
                    $client = app('website.http_client');

                    /** @var ResponseInterface $response */
                    $response = $client->request(
                        $options['method'],
                        $options['uri'],
                        $options['params']
                    );

                    if (200 === $response->getStatusCode()) {
                        $result = json_decode($response->getBody());
                    } else {
                        $result = [];
                    }

                    return $result;
                }
            );
        }

        return $result;
    }
}
