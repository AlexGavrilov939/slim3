<?php

namespace App\ApiSources;

use GuzzleHttp\Client;

/**
 * Class ApiSource
 * @package App\ApiSources
 */
abstract class AbstractApiSource
{
    const GET = 'GET';
    const POST = 'POST';

    protected $configId;
    protected $config;

    public function __construct($configId = 'default')
    {
        $this->config = $this->getConfig();
        $this->configId = $this->getConfigId($configId);
    }

    public function __call($method, $params)
    {
        $method = '_'.$method;
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
        throw new \Exception("No method {$method}");
    }

    /**
     * @param $uri
     * @param string $requestType
     * @param array $params
     * @param null $postProcessCallback
     * @return array
     */
    public function getResult($uri, $requestType = self::GET, array $params = [], $postProcessCallback = null)
    {
        if (isset($this->config['defaultParams'])) {
            $params = array_merge($this->config['defaultParams'], $params);
        }

        $options = [];
        $paramsOptionsKey = $requestType === self::GET ? 'query' : 'form_params';
        $options[$paramsOptionsKey] = $params;

        try {
            $client = new Client();
            $url = $this->config['gateway'].$uri. '/';
            $response =  $client->request($requestType, $url, $options);
            $resBody = $response->getBody()->getContents();
            $resHeaders = $response->getHeaders();
            $status = $response->getStatusCode();
        } catch (\Exception $e) {
            \Log::error('[ERROR] Api call with error!', [
                'uri' => $uri,
                'requestType' => $requestType,
                'params' => $params
            ]);
            return false;
        }

        if (is_callable($postProcessCallback)) {
            $resBody = $postProcessCallback($resBody);
        }

        return [
            'response' => [
                'status' => $status,
                'body' => $resBody,
                'headers' => $resHeaders
            ],
            'request' => [
                'method' => $requestType,
                'url' => $this->config['gateway'] . $uri,
                'params' => $params
            ]
        ];
    }

    public function setConfig($configId)
    {
        $sourceConfig = $this->getSourceConfigs();
        if (isset($sourceConfig[$configId])) {
            $this->config = $sourceConfig[$configId];
            return true;
        }

        return false;
    }

    /**
     * Возвращает установленный конфиг (sourceConfigs[configId])
     * @return array
     * @throws \Exception
     */
    public function getConfig()
    {
        $configId = $this->configId;
        $sourceConfig = $this->getSourceConfigs();
        if (isset($sourceConfig[$configId])) {
            return $sourceConfig[$configId];
        } elseif (isset($sourceConfig['default'])) {
            return $sourceConfig['default'];
        } else {
            throw new \Exception('Failed to load API config for ' . get_class($this) . ' service');
        }
    }

    /**
     * Возвращает текущий configId
     *
     * @param string $configId
     * @return string
     */
    public function getConfigId($configId = 'default')
    {
        return !is_null($this->configId) ? $this->configId : $configId;
    }

    protected function setConfigId($configId)
    {
        $this->configId = $configId;
    }

    /**
     * Возвращает все конфиги Api source'a
     */
    public function getSourceConfigs()
    {
        return config('api')[get_class($this)];
    }
}