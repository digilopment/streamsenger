<?php
(new class {

    const FILE = '../storage/messages.json';

    public function run()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_encode([
                'name' => $_POST['name'],
                'message' => $_POST['message'],
                'timestamp' => $_POST['timestamp'],
            ]);
            file_put_contents(self::FILE, $data . "\n", FILE_APPEND);
        }
    }
})->run();
