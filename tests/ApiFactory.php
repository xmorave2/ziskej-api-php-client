<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi;

use Http\Message\Authentication\Bearer;
use Monolog\Logger;
use Symfony\Component\Dotenv\Dotenv;

class ApiFactory
{

    public static function createApi(): Api
    {
        $api = new Api(
            new ApiClient(
                null,
                new Logger('ZiskejApi')
            )
        );


        $dotEnv = new Dotenv();
        $dotEnv->load(__DIR__.'/.env');

        $token = $api->login($_ENV['username'], $_ENV['password']);

        //@todo store token

        return new Api(
            new ApiClient(
                new Bearer($token),
                new Logger('ZiskejApi')
            )
        );
    }

}
