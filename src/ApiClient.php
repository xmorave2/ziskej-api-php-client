<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi;

use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\Formatter\FullHttpMessageFormatter;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use function GuzzleHttp\Psr7\stream_for;

final class ApiClient
{

    /**
     * Base URI of the client
     *
     * @var string|\Psr\Http\Message\UriInterface
     */
    private $baseUri = 'https://ziskej-test.techlib.cz:9080/api/v1';

    /**
     * @var \Http\Client\HttpClient
     */
    private $client;

    /**
     * @var \Http\Message\Authentication|null
     */
    private $authentication = null;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    private $logger = null;

    /**
     * @var mixed[]
     */
    private $plugins = [];

    public function __construct(
        ?Authentication $authentication,
        ?LoggerInterface $logger
    ) {
        $this->authentication = $authentication;
        $this->logger = $logger;

        // set base uri
        $this->plugins[] = new BaseUriPlugin(UriFactoryDiscovery::find()->createUri($this->baseUri), [
            'replace' => true,
        ]);

        if ($this->authentication) {
            $this->plugins[] = new AuthenticationPlugin($this->authentication);
        }

        if ($this->logger) {
            $formater = new FullHttpMessageFormatter();
            $this->plugins[] = new LoggerPlugin($this->logger, $formater);
        }

        $this->client = new PluginClient(HttpClientDiscovery::find(), $this->plugins);
    }

    /**
     * Send HTTP request and get response
     *
     * @param \Mzk\ZiskejApi\RequestObject $requestObject
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Http\Client\Exception
     */
    public function sendRequest(RequestObject $requestObject): ResponseInterface
    {
        $messageFactory = MessageFactoryDiscovery::find();

        if ($requestObject->getMethod() === 'POST' && !empty($requestObject->getParamsData())) {
            // POST request with form values
            $streamFactory = StreamFactoryDiscovery::find();
            $builder = new MultipartStreamBuilder($streamFactory);
            /**
             * @var string $key
             * @var mixed $val
             */
            foreach ($requestObject->getParamsData() as $key => $val) {
                if (is_array($val)) {
                    //@todo!!! how to send array as post parameter?
                    continue;
                    //$val = json_encode($val);
                }
                $builder->addResource((string)$key, $val);
            }
            $boundary = $builder->getBoundary();
            $headers = [
                'Content-Type' => 'multipart/form-data; boundary="' . $boundary . '"',
            ];
            $body = $builder->build();
        } else {
            // other requests
            $headers = [
                'Content-Type' => 'application/json',
            ];
            $body = stream_for(json_encode($requestObject->getParamsData()));
        }

        $request = $messageFactory->createRequest(
            $requestObject->getMethod(),
            $requestObject->getUri(),
            $headers,
            $body
        );

        return $this->client->sendRequest($request);
    }

}
