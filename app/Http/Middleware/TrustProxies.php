<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class TrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string
     */
    protected $proxies = ['192.168.7.21'];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    // protected $headers = Request::HEADER_X_FORWARDED_ALL;
    protected $headers =
    Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
