<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1e260f5a2586309e6753328bc15ccdc3
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Postpay\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Postpay\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Postpay\\Exceptions\\ApiException' => __DIR__ . '/../..' . '/src/Exceptions/ApiException.php',
        'Postpay\\Exceptions\\GraphQLException' => __DIR__ . '/../..' . '/src/Exceptions/GraphQLException.php',
        'Postpay\\Exceptions\\PostpayException' => __DIR__ . '/../..' . '/src/Exceptions/PostpayException.php',
        'Postpay\\Exceptions\\RESTfulException' => __DIR__ . '/../..' . '/src/Exceptions/RESTfulException.php',
        'Postpay\\HttpClients\\Client' => __DIR__ . '/../..' . '/src/HttpClients/Client.php',
        'Postpay\\HttpClients\\ClientInterface' => __DIR__ . '/../..' . '/src/HttpClients/ClientInterface.php',
        'Postpay\\HttpClients\\Curl' => __DIR__ . '/../..' . '/src/HttpClients/Curl.php',
        'Postpay\\HttpClients\\CurlClient' => __DIR__ . '/../..' . '/src/HttpClients/CurlClient.php',
        'Postpay\\HttpClients\\GuzzleClient' => __DIR__ . '/../..' . '/src/HttpClients/GuzzleClient.php',
        'Postpay\\Http\\Request' => __DIR__ . '/../..' . '/src/Http/Request.php',
        'Postpay\\Http\\Response' => __DIR__ . '/../..' . '/src/Http/Response.php',
        'Postpay\\Http\\Signature' => __DIR__ . '/../..' . '/src/Http/Signature.php',
        'Postpay\\Http\\Url' => __DIR__ . '/../..' . '/src/Http/Url.php',
        'Postpay\\Postpay' => __DIR__ . '/../..' . '/src/Postpay.php',
        'Postpay\\Serializers\\Date' => __DIR__ . '/../..' . '/src/Serializers/Date.php',
        'Postpay\\Serializers\\Decimal' => __DIR__ . '/../..' . '/src/Serializers/Decimal.php',
        'postpay' => __DIR__ . '/../..' . '/Postpay.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1e260f5a2586309e6753328bc15ccdc3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1e260f5a2586309e6753328bc15ccdc3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1e260f5a2586309e6753328bc15ccdc3::$classMap;

        }, null, ClassLoader::class);
    }
}
