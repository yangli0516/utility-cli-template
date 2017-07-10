<?php

namespace UtilityCli\Helper;

use GuzzleHttp\Client;

class HttpHeader
{
    private $response = NULL;

    /**
     * HttpHeader constructor.
     *
     * @param string $url
     */
    public function __construct($url) {
        $client = new Client();
        try {
            $this->response = $client->head($url, ['http_errors' => false]);
            $client = NULL;
        } catch (\Exception $e) {

        }
    }

    /**
     * Check if the header response is 200.
     *
     * @return bool
     */
    public function exists() {
        return (isset($this->response) && $this->response->getStatusCode() === 200);
    }

    /**
     * Get the content type from the header.
     *
     * @return string|null
     */
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

    /**
     * Get the proposed filename from the content-disposition header.
     *
     * @return string|null
     */
    public function getFileName() {
        if (isset($this->response) && $this->response->getStatusCode() === 200) {
            $header = $this->response->getHeader('Content-Disposition');
            if ($header) {
                $disposition = $header[0];
                if (preg_match('/filename\=\"?([^"]+)\"?/i', $disposition, $matches)) {
                    return $matches[1];
                }
            }
        }
        return NULL;
    }
}
