<?php
    session_start()
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="signin.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="events.php">Evenements</a></li>
            </ul>
        </nav>
    </header>
    <h1>evenements</h1>

    <?php 
        require_once "./config/connect.php";
        $req = "SELECT title_event, description_event, date_event, place_event,id_event FROM events";
        $data = $db->prepare($req);
        $data->execute();
        $events= $data->fetchAll();

        foreach ($events as $event){
            ?>
            <article>
                <h2><?= $event['title_event'] ?></h2>
                <h3><?= $event['date_event']  ?></h3>
                <p><?= $event['place_event']  ?></p>
                <p><?= $event['description_event']  ?></p>
                <?php 
                    if ($_SESSION['role'] == 2){
                       ?><a href="event_booking.php?id=<?= $event['id_event'] ?>"><button>modifier</button></a>
                    <?php
                    }
                ?>
                <a href="event_booking.php?id=<?= $event['id_event'] ?>"><button>s'inscrire</button></a>
            </article>

        <?php }
        


    ?>

</body>
</html>