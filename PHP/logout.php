<?php
    session_start();
    session_unset();   // remove variáveis da sessão
    session_destroy(); // apaga a sessão
    header("Location: ../HTML/index.html"); // volta ao login
    exit;