<?php

use Phpro\SoapClient\ClientBuilder;
use Phpro\SoapClient\ClientFactory;
use Profit\Nordea\API\Middleware\WsseMiddleware;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once __DIR__ . '/bootstrap.php';

$wsdl = __DIR__ . '/../wsdl/BankCorporateFileService_20080616.xml';
$clientFactory = new ClientFactory(\Profit\Nordea\API\NordeaSoapClient::class);
$soapOptions = [
    'cache_wsdl' => WSDL_CACHE_NONE
];

$clientBuilder = new ClientBuilder($clientFactory, $wsdl, $soapOptions);
//$clientBuilder->withLogger(new Logger());
$clientBuilder->withEventDispatcher(new EventDispatcher());

use Phpro\SoapClient\Soap\ClassMap\ClassMap;

$types = [
    new ClassMap('RequestHeader', \Profit\Nordea\API\SoapTypes\RequestHeader::class),
    new ClassMap('ResponseHeader', \Profit\Nordea\API\SoapTypes\ResponseHeader::class),
    new ClassMap('UploadFileRequest', \Profit\Nordea\API\SoapTypes\UploadFileRequest::class),
    new ClassMap('UploadFileResponse', \Profit\Nordea\API\SoapTypes\UploadFileResponse::class),
    new ClassMap('DownloadFileListRequest', \Profit\Nordea\API\SoapTypes\DownloadFileListRequest::class),
    new ClassMap('DownloadFileListResponse', \Profit\Nordea\API\SoapTypes\DownloadFileListResponse::class),
    new ClassMap('DownloadFileRequest', \Profit\Nordea\API\SoapTypes\DownloadFileRequest::class),
    new ClassMap('DownloadFileResponse', \Profit\Nordea\API\SoapTypes\DownloadFileResponse::class),
    new ClassMap('DeleteFileRequest', \Profit\Nordea\API\SoapTypes\DeleteFileRequest::class),
    new ClassMap('DeleteFileResponse', \Profit\Nordea\API\SoapTypes\DeleteFileResponse::class),
    new ClassMap('GetUserInfoRequest', \Profit\Nordea\API\SoapTypes\GetUserInfoRequest::class),
    new ClassMap('GetUserInfoResponse', \Profit\Nordea\API\SoapTypes\GetUserInfoResponse::class),
    new ClassMap('FileServiceFaultDetail', \Profit\Nordea\API\SoapTypes\FileServiceFaultDetail::class),
    new ClassMap('ApplicationResponse', \Profit\Nordea\API\SoapTypes\ApplicationResponse::class)
];

foreach($types as $type){
    $clientBuilder->addClassMap($type);
}


$clientBuilder->withHandler(\Phpro\SoapClient\Soap\Handler\HttPlugHandle::createWithDefaultClient());

$wsse = new WsseMiddleware($key_file, $key_file);
//$wsse->withAllHeadersSigned()->withEncryption($key_file);
$wsse->withTimestamp(0);
$clientBuilder->addMiddleware($wsse);

/** @var \Profit\Nordea\API\NordeaSoapClient $client */
$client = $clientBuilder->build();

$client->changeSoapLocation('https://filetransfer.nordea.com/services/CorporateFileService');

/** @var \Profit\Nordea\API\SoapTypes\GetUserInfoResponse $response */
$response = $client->getUserInfo(new \Profit\Nordea\API\SoapTypes\GetUserInfoRequest($config));
echo $response->getResponseHeader()->getResponseText();