<?php
session_start();

if (!isset($_SESSION['idUser'])) {
    header('Location: signin.php');
    exit;
}

require_once "./config/connect.php";
include "./header.php";
?>


<h1>evenements</h1>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
    <a href="event_create.php"><button>+ Créer un événement</button></a>
<?php endif; ?>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
    <a href="popularity_event.php"><button>Analyser la popularité</button></a>
<?php endif; ?>

<?php 
    require_once "./config/connect.php";
    $req = "SELECT title_event, description_event, date_event, place_event, id_event, fk_id_user FROM events";
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
                if ($_SESSION['role'] == 2 && $_SESSION['idUser'] == $event['fk_id_user']){
                    ?><a href="event_edit.php?id=<?= $event['id_event'] ?>"><button>modifier</button></a><?php
                }
                }
                if ($_SESSION['role'] == 2 && $_SESSION['idUser'] == $event['fk_id_user']){
                    ?>
                    <a href="event_participants.php?id=<?= $event['id_event'] ?>"><button>Voir les participants</button></a>
                    <?php
                }
            ?>
            <a href="event_booking.php?id=<?= $event['id_event'] ?>"><button>s'inscrire</button></a>
        </article>

    <?php 
    


?>

</body>
</html>