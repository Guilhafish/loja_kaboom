<?php
    session_start();
    session_unset();   // remove variáveis da sessão
    session_destroy(); // apaga a sessão
    header("Location: index.php"); // volta ao login
    exit;