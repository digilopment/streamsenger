<?php
(new class {

    const FILE = '../storage/messages.json';

    public function __construct()
    {
        header('Content-Type: text/event-stream; charset=utf-8');
        header('Cache-Control: no-cache,no-store');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');
        header('Connection: keep-alive');
        header('Access-Control-Allow-Origin: *');
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
            } else {
                echo "\n";
                @ob_flush();
                flush();
                usleep(0.125 * 1000000);
            }
            fclose($file);
        }
    }

})->run();
