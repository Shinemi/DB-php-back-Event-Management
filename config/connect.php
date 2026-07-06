<?php

try {
    $db = new PDO(
        'mysql:host=localhost;dbname=events;charset=utf8',
        'root',
        '',
        [PDO :: ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION]
    );
} catch (\Throwable $th) {
    echo 'connexion refusée a la bdd';
    exit();
}

?>