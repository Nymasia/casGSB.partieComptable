<?php
/**
 * Fonctions pour l'application GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

/**
 * Teste si un quelconque visiteur est connecté
 *
 * @return vrai ou faux
 */
function estConnecte()
{
    return isset($_SESSION['idComptable']);
}

/**
 * Enregistre dans une variable session les infos d'un visiteur
 *
 * @param String $idComptable ID du visiteur
 * @param String $nom        Nom du visiteur
 * @param String $prenom     Prénom du visiteur
 *
 * @return null
 */
function connecter($idComptable, $nom, $prenom)
{
    $_SESSION['idComptable'] = $idComptable;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
}

/**
 * Détruit la session active
 *
 * @return null
 */
function deconnecter()
{
    session_destroy();
}

/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais
 * aaaa-mm-jj
 *
 * @param String $maDate au format  jj/mm/aaaa
 *
 * @return Date au format anglais aaaa-mm-jj
 */
function dateFrancaisVersAnglais($maDate)
{
    list($jour, $mois, $annee) = explode('/', $maDate);
    return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
}

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format
 * français jj/mm/aaaa
 *
 * @param String $maDate au format  aaaa-mm-jj
 *
 * @return Date au format format français jj/mm/aaaa
 */
function dateAnglaisVersFrancais($maDate)
{
    list($annee, $mois, $jour) = explode('-', $maDate);
    $date = $jour . '/' . $mois . '/' . $annee;
    return $date;
}

/**
 * Retourne le mois au format aaaamm selon le jour dans le mois
 *
 * @param String $date au format  jj/mm/aaaa
 *
 * @return String Mois au format aaaamm
 */
function getMois($date)
{
    @list ($jour, $mois, $annee) = explode('/', $date);
    unset($jour);
    if (strlen($mois) == 1) {
        $mois = '0' . $mois;
    }
    return $annee . $mois;
}

/* gestion des erreurs */

/**
 * Inverse la présentation du libellé aaaamm en mm/aaaa
 *
 * param string $leLibelle au format aaaamm
 * return string le mois au format mm/aaaa
 */
function inverseMois(string $leLibelle): string
{
    $annee = substr($leLibelle, 0, 4);
    $mois = substr($leLibelle, 4, 2);
    return $mois . "/" . $annee;
}

/**
 * Retourne le mois en Français (en minuscule) avec l'année
 *
 * param string $leLibelle au format aaaamm
 * return string de type juillet 2020
 */
function moisEnLettre(string $leLibelle): string
{
    $lesMois = [
        "janvier",
        "février",
        "mars",
        "avril",
        "mai",
        "juin",
        "juillet",
        "août",
        "septembre",
        "octobre",
        "novembre",
        "décembre"
    ];
    $annee = substr($leLibelle, 0, 4);
    $mois = substr($leLibelle, 4, 2);
    return $lesMois[$mois - 1] . " " . $annee;
}

/* gestion des erreurs */

/**
 * Indique si une valeur est un entier positif ou nul
 *
 * param Integer $valeur Valeur
 *
 * return Boolean vrai ou faux
 */
function estEntierPositif($valeur)
{
    return preg_match('/[^0-9]/', $valeur) == 0;
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 *
 * param Array $tabEntiers Un tableau d'entier
 *
 * return Boolean vrai ou faux
 */
function estTableauEntiers($tabEntiers)
{
    $boolReturn = true;
    foreach ($tabEntiers as $unEntier) {
        if (!estEntierPositif($unEntier)) {
            $boolReturn = false;
        }
    }
    return $boolReturn;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 *
 * param String $dateTestee Date à tester
 *
 * return Boolean vrai ou faux
 */
function estDateDepassee($dateTestee)
{
    $dateActuelle = date('d/m/Y');
    @list ($jour, $mois, $annee) = explode('/', $dateActuelle);
    $annee --;
    $anPasse = $annee . $mois . $jour;
    @list ($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
    return ($anneeTeste . $moisTeste . $jourTeste < $anPasse);
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 *
 * param String $date Date à tester
 *
 * return Boolean vrai ou faux
 */
function estDateValide($date)
{
    $tabDate = explode('/', $date);
    $dateOK = true;
    if (count($tabDate) != 3) {
        $dateOK = false;
    } else {
        if (!estTableauEntiers($tabDate)) {
            $dateOK = false;
        } else {
            if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                $dateOK = false;
            }
        }
    }
    return $dateOK;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques
 *
 * param Array $lesFrais Tableau d'entier
 *
 * return Boolean vrai ou faux
 */
function lesQteFraisValides($lesFrais)
{
    return estTableauEntiers($lesFrais);
}

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais
 * et le montant
 *
 * details Des message d'erreurs sont ajoutés au tableau des erreurs
 *
 * param String $dateFrais Date des frais
 * param String $libelle Libellé des frais
 * param Float $montant Montant des frais
 *
 * return null
 */
function valideInfosFrais($dateFrais, $libelle, $montant)
{
    if ($dateFrais == '') {
        ajouterErreur('Le champ date ne doit pas être vide');
    } else {
        if (!estDatevalide($dateFrais)) {
            ajouterErreur('Date invalide');
        } else {
            if (estDateDepassee($dateFrais)) {
                ajouterErreur(
                    "date d'enregistrement du frais dépassé, plus de 1 an");
            }
        }
    }
    if ($libelle == '') {
        ajouterErreur('Le champ description ne peut pas être vide');
    }
    if ($montant == '') {
        ajouterErreur('Le champ montant ne peut pas être vide');
    } elseif (!is_numeric($montant)) {
        ajouterErreur('Le champ montant doit être numérique');
    }
}

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 *
 * param String $msg Libellé de l'erreur
 *
 * return null
 */
function ajouterErreur(string $msg)
{
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    $_REQUEST['erreurs'][] = $msg;
    if (isset($_SESSION['nom'])) {
        addLogEvent(
            'Erreur ("' . $msg . '") de ' . $_SESSION['prenom'] . ' ' .
            $_SESSION['nom'] . ' (IP = ' . $_SERVER['REMOTE_ADDR']);
    }
}

/**
 * Retoune le nombre de lignes du tableau des erreurs
 *
 * return Integer le nombre d'erreurs
 */
function nbErreurs()
{
    if (!isset($_REQUEST['erreurs'])) {
        return 0;
    } else {
        return count($_REQUEST['erreurs']);
    }
/**
 * Ajoute le libellé d'une information au tableau des informations
 *
 * param String $msg Libellé de l'information
 *
 * return null
 */
}

function ajouterInfo($msg)
{
    if (!isset($_REQUEST['infos'])) {
        $_REQUEST['infos'] = array();
    }
    $_REQUEST['infos'][] = $msg;
    addLogEvent(
        'Info ("' . $msg . '") de ' . $_SESSION['prenom'] . ' ' .
        $_SESSION['nom'] . ' (IP = ' . $_SERVER['REMOTE_ADDR']);
}

/**
 * Retoune le nombre de lignes du tableau des informations
 *
 * return Integer le nombre d'informations
 */
function nbInfos()
{
    if (!isset($_REQUEST['infos'])) {
        return 0;
    } else {
        return count($_REQUEST['infos']);
    }
}

/**
 * Retourne le nombre de fiches de plus d'un an dans le tableau passé en paramètre
 *
 * param array tableau de fiches de visiteurs
 * return integer nb de fiches périmées
 */
function compterFichesPerimees(array $tableauDeFiches): int
{
    // Comptabiliser les fiches de plus de 1 an
    $compteur = 0;
    foreach ($tableauDeFiches as $uneFiche) {
        if (!estDateDepassee(dateAnglaisVersFrancais($uneFiche['date']))) {
            $compteur ++;
        }
    }
    return $compteur;
}

/**
 * Retourne le montant total des fiches dans le tableau passé en paramètre
 *
 * param array tableau de fiches de visiteurs
 * return float montant total
 */
function compterMontantTotal(array $tableauDeFiches): float
{
    // Comptabiliser les fiches de plus de 1 an
    $montant = 0;
    foreach ($tableauDeFiches as $uneFiche) {
        if (!estDateDepassee(dateAnglaisVersFrancais($uneFiche['date']))) {
            $montant += (float) $uneFiche['montant'];
        }
    }
    return $montant;
}

/**
 * Ajoute l'événement (avec TimeStamp) au fichier GSB2020.log
 *
 * param string $event
 */
function addLogEvent($event)
{
    $time = date("D, d M Y H:i:s");
    $time = "[" . $time . "] ";
    $event = $time . $event . "\n";
    file_put_contents("GSB2020.log", $event, FILE_APPEND);
}
