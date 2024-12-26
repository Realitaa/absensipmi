<?php

namespace App\Controllers;

class TimeController extends BaseController
{
    public function getServerTime()
    {
        // Kembalikan waktu server dalam format JSON
        return $this->response->setJSON([
            'serverTime' => time() * 1000 // Waktu server dalam milidetik
        ]);
    }
}
