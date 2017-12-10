<?php
/**
 * Created by PhpStorm.
 * User: JiminikJilis
 * Date: 18.09.2017
 * Time: 17:33
 */

namespace Tinify;


class CrazyClient extends Client
{
    const API_ENDPOINT = "https://tinypng.com/web";

    function __construct($key = 'crazy', $app_identifier = NULL, $proxy = NULL)
    {
        parent::__construct($key, $app_identifier, $proxy);

        unset(
            $this->options[CURLOPT_USERPWD],
            $this->options[CURLOPT_CAINFO],
            $this->options[CURLOPT_SSL_VERIFYPEER]
        );

        $this->options[CURLOPT_USERAGENT] = join(" ", array_filter(array('Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36')));

    }

    function request($method, $url, $body = NULL) {
        $header = array();
        if (is_array($body)) {
            if (!empty($body)) {
                $body = json_encode($body);
                array_push($header, "Content-Type: application/json");
            } else {
                $body = NULL;
            }
        }

        for ($retries = self::RETRY_COUNT; $retries >= 0; $retries--) {
            if ($retries < self::RETRY_COUNT) {
                usleep(self::RETRY_DELAY * 1000);
            }

            $request = curl_init();
            if ($request === false || $request === null) {
                throw new ConnectionException(
                    "Error while connecting: curl extension is not functional or disabled."
                );
            }

            curl_setopt_array($request, $this->options);

            $url = strtolower(substr($url, 0, 6)) == "https:" ? $url : self::API_ENDPOINT . $url;

            curl_setopt($request, CURLOPT_URL, $url);
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, strtoupper($method));

            if (count($header) > 0) {
                curl_setopt($request, CURLOPT_HTTPHEADER, $header);
            }

            if ($body) {
                curl_setopt($request, CURLOPT_POSTFIELDS, $body);
            }

            $response = curl_exec($request);

            if (is_string($response)) {
                $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
                $headerSize = curl_getinfo($request, CURLINFO_HEADER_SIZE);
                curl_close($request);

                $headers = self::parseHeaders(substr($response, 0, $headerSize));
                $body = substr($response, $headerSize);

                if (isset($headers["compression-count"])) {
                    Tinify::setCompressionCount(intval($headers["compression-count"]));
                }

                if ($status >= 200 && $status <= 299) {
                    return (object) array("body" => $body, "headers" => $headers);
                }

                $details = json_decode($body);
                if (!$details) {
                    $message = sprintf("Error while parsing response: %s (#%d)",
                        PHP_VERSION_ID >= 50500 ? json_last_error_msg() : "Error",
                        json_last_error());
                    $details = (object) array(
                        "message" => $message,
                        "error" => "ParseError"
                    );
                }

                if ($retries > 0 && $status >= 500) continue;
                throw Exception::create($details->message, $details->error, $status);
            } else {
                $message = sprintf("%s (#%d)", curl_error($request), curl_errno($request));
                curl_close($request);
                if ($retries > 0) continue;
                throw new ConnectionException("Error while connecting: " . $message);
            }
        }
    }

}