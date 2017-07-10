<?php

namespace UtilityCli\Helper;

use GuzzleHttp\Client;

class HttpHeader
{
    private $response = NULL;

    public function __construct($url) {
        $client = new Client();
        try {
            $this->response = $client->head($url, ['http_errors' => false]);
            $client = NULL;
        } catch (\Exception $e) {

        }
    }

    public function exists() {
        return (isset($this->response) && $this->response->getStatusCode() === 200);
    }

    public function getContentType() {
        if (isset($this->response) && $this->response->getStatusCode() === 200) {
            $header = $this->response->getHeader('Content-Type');
            if ($header) {
                $contentType = $header[0];
                $parts = explode(';', $contentType);
                return $parts[0];
            }
        }
        return NULL;
    }

    public function getFileName() {
        if (isset($this->response) && $this->response->getStatusCode() === 200) {
            $header = $this->response->getHeader('Content-Disposition');
            if ($header) {
                $disposition = $header[0];
                if (preg_match('/filename\=\"([^"]+)\"/i', $disposition, $matches)) {
                    return $matches[1];
                }
            }
        }
        return NULL;
    }
}
