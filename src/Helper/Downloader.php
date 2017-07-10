<?php

namespace UtilityCli\Helper;


use GuzzleHttp\Client;

class Downloader
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Download a file.
     *
     * @param string $url The file URL.
     * @param string $filename The file name to be saved.
     * @param string $dir The directory path to save the file.
     * @return null|string The saved file path if success. Otherwise
     *   returns null.
     */
    public function saveTo($url, $filename, $dir)
    {
        try {
            $response = $this->client->get($url, ['http_errors' => false]);
            if ($response->getStatusCode() === 200) {
                $saveto = self::fileSaveName($filename, $dir);
                $result = file_put_contents($saveto, $response->getBody());
                if ($result !== false) {
                    return realpath($saveto);
                }
            }
        } catch (\Exception $e) {

        }
        return null;
    }

    /**
     * Resolve the file duplicate names when saving a file.
     *
     * @param string $filename The intended file saved name.
     * @param string $dir The directory to save the file.
     * @return string The full path of the file once the name has been
     *   resolved.
     */
    public static function fileSaveName($filename, $dir)
    {
        $parts = pathinfo($filename);
        if (file_exists($dir . DIRECTORY_SEPARATOR . $filename)) {
            $i = 0;
            $saveto = $dir . DIRECTORY_SEPARATOR . $parts['filename'] . '_' . $i . '.' . $parts['extension'];
            while (file_exists($saveto)) {
                $i++;
                $saveto = $dir . DIRECTORY_SEPARATOR . $parts['filename'] . '_' . $i . '.' . $parts['extension'];
            }
        } else {
            $saveto = $dir . DIRECTORY_SEPARATOR . $filename;
        }
        return $saveto;
    }
}
