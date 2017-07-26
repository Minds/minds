<?php
namespace Minds\Helpers;

class Upload
{
    public static function parsePhpInput()
    {
        $input = fopen("php://input", "r");

        $raw = '';
        while ($data = fread($input, 1024)) {
            $raw .= $data;
        }

        $boundary = substr($raw, 0, strpos($raw, "\r\n"));
        $parts = array_slice(explode($boundary, $raw), 1);

        foreach ($parts as $part) {
            // If this is the last part, break
            if ($part == "--\r\n") {
                break;
            }

            // Separate content from headers
            $part = ltrim($part, "\r\n");
            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);
        }

        fclose($input);
        return ['headers' => $raw_headers, 'body' => $body];
    }
}
