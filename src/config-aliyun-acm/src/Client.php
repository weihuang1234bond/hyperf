<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Hyperf\ConfigAliyunAcm;

use Closure;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Guzzle\ClientFactory as GuzzleClientFactory;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;
use RuntimeException;

class Client implements ClientInterface
{
    /**
     * @var array
     */
    public $fetchConfig;

    /**
     * @var Closure
     */
    private $client;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $servers;

    public function __construct(ContainerInterface $container)
    {
        /**
         * @var GuzzleClientFactory $clientFactory
         */
        $clientFactory = $container->get(GuzzleClientFactory::class);
        $this->client = $clientFactory->create();
        $this->config = $container->get(ConfigInterface::class);
        $this->logger = $container->get(StdoutLoggerInterface::class);
    }

    public function pull(): array
    {
        $client = $this->client;
        if (! $client instanceof \GuzzleHttp\Client) {
            throw new RuntimeException('aliyun acm: Invalid http client.');
        }

        // ACM config
        $endpoint = $this->config->get('aliyun_acm.endpoint', 'acm.aliyun.com');
        $namespace = $this->config->get('aliyun_acm.namespace', '');
        $dataId = $this->config->get('aliyun_acm.data_id', '');
        $group = $this->config->get('aliyun_acm.group', 'DEFAULT_GROUP');
        $accessKey = $this->config->get('aliyun_acm.access_key', '');
        $secretKey = $this->config->get('aliyun_acm.secret_key', '');

        // Sign
        $timestamp = round(microtime(true) * 1000);
        $sign = base64_encode(hash_hmac('sha1', "{$namespace}+{$group}+{$timestamp}", $secretKey, true));

        try {
            if (! $this->servers) {
                // server list
                $response = $client->get("http://{$endpoint}:8080/diamond-server/diamond");
                if ($response->getStatusCode() !== 200) {
                    throw new RuntimeException('Get server list failed from Aliyun ACM.');
                }
                $this->servers = array_filter(explode("\n", $response->getBody()->getContents()));
            }
            $server = $this->servers[array_rand($this->servers)];

            // Get config
            $response = $client->get("http://{$server}:8080/diamond-server/config.co", [
                'headers' => [
                    'Spas-AccessKey' => $accessKey,
                    'timeStamp' => $timestamp,
                    'Spas-Signature' => $sign,
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'
                ],
                'query' => [
                    'tenant' => $namespace,
                    'dataId' => $dataId,
                    'group' => $group,
                ],
            ]);
            if ($response->getStatusCode() !== 200) {
                throw new RuntimeException('Get config failed from Aliyun ACM.');
            }
            $content = $response->getBody()->getContents();
            if (! $content) {
                return [];
            }
            return Json::decode($content);
        } catch (\Throwable $throwable) {
            $this->logger->error(sprintf('%s[line:%d] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
            return [];
        }
    }
}
