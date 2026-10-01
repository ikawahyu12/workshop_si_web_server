<?php

class HomeController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        echo "<h1>Selamat Datang di SI Akademik</h1>";
        echo "<p>Ini adalah halaman Home Acara 5.</p>";

        if (!empty($_SESSION['flash_message'])) {

            echo "<div style='
                padding: 10px;
                margin: 15px 0;
                background-color: #d1e7dd;
                color: #0f5132;
                border: 1px solid #badbcc;
                border-radius: 5px;
            '>";

            echo $_SESSION['flash_message'];

            echo "</div>";

            unset($_SESSION['flash_message']);
        }
    }
}