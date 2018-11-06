<?php


namespace Profit\Nordea\API\Middleware;


use Phpro\SoapClient\Xml\SoapXml;
use Psr\Http\Message\RequestInterface;
use RobRichards\WsePhp\WSSESoap;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use Psr\Http\Message\ResponseInterface;

class WsseMiddleware extends \Phpro\SoapClient\Middleware\WsseMiddleware
{
}