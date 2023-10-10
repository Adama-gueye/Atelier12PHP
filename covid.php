<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php

$erreur=[];
session_start();
$regex = '/^[A-Z][\p{L}-]*$/';
if (!isset($_SESSION['historique'])) {
    $_SESSION['historique'] = [];
}

if (isset($_POST['submit'])) {
   extract($_POST);

   if(empty($mt)|| empty($nom)|| empty($prenom)||empty($age)||empty($toux)||empty($poid)|| empty($d)||empty($perteO) ||empty($temperature)){
        $erreur[]="Tous les champs sont obligatoire";
   }elseif($temperature < 34 || $temperature>40){
        $erreur[]="Veillez saisir une température comprise entre 34 et 40";  
   }elseif($poid < 4 && $poid > 400) {
        $erreur[]="Veillez saisir un poid comprise entre 4 et 400";  
    }elseif(!(preg_match($regex, $nom) && preg_match($regex, $prenom))) {
        $erreur[]="Une erreur s'est produit sur la saisie de nom ou de prenom";
    }
   else{
        $score = 0;

        if ($mt == "oui")
            $score += 10;
        if ($d == "oui")
            $score += 10;
        if ($temperature == "oui")
            $score += 20;
        if ($toux == "oui")
            $score += 20;
        if ($perteO == "oui")
            $score += 20;
        if ($age == "0-14")
            $score += 20;
        if ($age == "15-30")
            $score += 10;
        if ($age == "31-100")
            $score += 20;

        $message = "";

        if ($score >= 80) {
            $message = "<p style='color: red;'>Critique : Risque élevé de COVID-19</p>";
        } elseif($score >= 50 && $score <= 79) {
            $message = "<p style='color: yellow;'>Pas critique : Risque modéré de COVID-19</p>";
        } else {
            $message = "<p style='color: greenyellow;'>Pas de COVID</p>";
        }

        $saisieActuelle = [
            "Nom" => $nom,
            "Prénom" => $prenom,
            "Poids" => $poid,
            "Température" => $temperature,
            "Maux de Tête" => $mt,
            "Diarrhée" => $d,
            "Toux" => $toux,
            "Perte Odorat" => $perteO,
            "Âge" => $age,
            "Score" => $score,
            "Message" => $message
        ];

        $_SESSION['historique'][] = $saisieActuelle;
    }
    echo implode(". ",$erreur);
}

if (isset($_POST['supprimer'])) {
    $_SESSION['historique'] = [];
}
?>
<body>
    <form action="" method="post">
        <label for="nom">Nom</label><br>
        <input type="text" name="nom" <?php if($erreur!=[]){?> value="<?=$_POST["nom"]?>"<?php }?>><br>
        <label for="prenom">Prénom</label><br>
        <input type="text" name="prenom" <?php if($erreur!=[]){?> value="<?=$_POST["prenom"]?>"<?php }?>><br>
        <label for="poid">Poids</label><br>
        <input type="text" name="poid" <?php if($erreur!=[]){?> value="<?=$_POST["poid"]?>"<?php }?>?><br>
        <label for="temperature">Température</label><br>
        <input type="text" name="temperature" <?php if($erreur!=[]){?> value="<?=$_POST["temperature"]?>"<?php }?>><br>
        <label for="mt">Maux de Tête</label><br>
        <input type="radio" name="mt" value = "oui" <?php if($erreur!=[]){if($mt=="oui")echo"checked"; else echo '';}?>>Oui
        <input type="radio" name="mt" value = "non"<?php if($erreur!=[]){if($mt=="non")echo"checked";}?>>Non<br>
        <label for="d">Diarrhée</label><br>
        <input type="radio" value="oui" name="d" <?php if($erreur!=[]){if($_POST['d']="oui")echo"checked";}?>>Oui
        <input type="radio" value="non" name="d" <?php if($erreur!=[]){if($_POST['d']=="non")echo"checked";}?>>Non<br>
        <label for="toux">Toux</label><br>
        <input type="radio" value="oui" name="toux" <?php if($erreur!=[]){if($_POST['toux']=="oui")echo"checked";}?>>Oui
        <input type="radio" value="non" name="toux" <?php if($erreur!=[]){if($_POST['toux']=="non")echo"checked";}?>>Non<br>
        <label for="perteO">Perte Odorat</label><br>
        <input type="radio" value="oui" name="perteO"<?php if($erreur!=[]){if($_POST['perteO']=="oui")echo"checked";}?>>Oui
        <input type="radio" value="non" name="perteO"<?php if($erreur!=[]){if($_POST['perteO']=="non")echo"checked";}?>>Non<br>
        <label for="age">Âge</label><br>
        <input type="radio" value="0-14" name="age" <?php if($erreur!=[]){if($_POST['age']=="0-14")echo"checked";}?>>0-14
        <input type="radio" value="15-30" name="age" <?php if($erreur!=[]){if($_POST['age']=="15-30")echo"checked";}?>>15-30
        <input type="radio" value="31-100" name="age" <?php if($erreur!=[]){if($_POST['age']=="31-100")echo"checked";}?>>31-100 <br><br>
        <button type="submit" name="submit" style="color: blue;">Test</button><br><br>
        <button type="submit" name="supprimer" style="color: red;">Supprimer l'historique</button>
    </form>

    <?php
     echo "<h3>Historique des saisies :</h3>";
     foreach ($_SESSION['historique'] as $saisie) {
        echo "<ul>";
        foreach ($saisie as $cle => $valeur) {
            if ($cle === "Score") {
                echo "<li>$cle : $valeur%</li>";
            } else {
                echo "<li>$cle : $valeur</li>";
            }
        }
        echo "</ul>";
     }
    ?>
</body>
</html>