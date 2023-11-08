<?php
(new class {

    const FILE = '../storage/messages.json';

    public function __construct()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-store');
        header('X-Accel-Buffering: no');
        header('Connection: keep-alive');
        header('Access-Control-Allow-Origin: *');
    }

    public function writeToFile()
    {
        $lastPosition = 0;
        if (!file_exists(self::FILE)) {
            file_put_contents(self::FILE, '');
        }
        while (true) {
            $file = fopen(self::FILE, 'r');

            $fileSize = filesize(self::FILE);

            if ($fileSize > $lastPosition) {
                fseek($file, $lastPosition);
                while (!feof($file)) {
                    $line = fgets($file);
                    echo "data: $line\n\n";
                    @ob_flush();
                    flush();
                }
                $lastPosition = ftell($file);
            }
        }
    }

    public function run()
    {

        $lastPosition = 0;
        if (!file_exists(self::FILE)) {
            file_put_contents(self::FILE, '');
        }
        while (true) {
            $file = fopen(self::FILE, 'r');
            clearstatcache();

            $fileSize = filesize(self::FILE);

            if ($fileSize > $lastPosition) {
                fseek($file, $lastPosition);
                while (!feof($file)) {
                    $line = fgets($file);
                    echo "data: $line\n\n";
                    @ob_flush();
                    flush();
                }
                $lastPosition = ftell($file);
            }
        }
    }

})->run();
