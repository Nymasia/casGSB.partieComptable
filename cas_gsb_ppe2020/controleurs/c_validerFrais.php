<?php
/**
 * Gestion de la validation des fiches de frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @author    Marion CASTEL
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

// On commence par cloturer toutes les fiches de frais du mois précédent
$pdo->clotureFichesMoisPrecedent();

// On récupère la fonction pour obtenir la liste de tous les visiteurs
$lesVisiteurs = $pdo->getListeDesVisiteurs();

// Si le visiteur a des fiches à cloturer on affiche les mois, sinon il ne se passe rien
$j = 0;
foreach ($lesVisiteurs as $unVisiteur){
    $unJeuDonnees = $pdo->getLesMoisAValider($unVisiteur['id']);
    if ($unJeuDonnees != null) {
        foreach ($unJeuDonnees as $Donnee) {
            $TouslesMois[$j] = array(
                'ID' => $unVisiteur['id'],
                'mois' => $Donnee['mois'],
                'numAnnee' => $Donnee['numAnnee'],
                'numMois' => $Donnee['numMois']
            );
            $j ++;
        }
        ;
    } else {
        unset($lesVisiteurs[array_search($unVisiteur, $lesVisiteurs)]);
    }
    
}
switch ($action){
        case 'selectionnerUtilisateur':
            include 'vues/v_listeVisiteurs.php';
            break;
        case'voirListeFrais' :
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs',FILTER_SANITIZE_STRING);
        $nomPrenom = $pdo->getNomVisiteur($idVisiteur);
        $moisFiche = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur,   $moisFiche);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$moisFiche);
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $moisFiche);
        include 'vues/v_listeVisiteurs.php';
        include 'vues/v_validerFrais.php';
        include 'vues/v_validerFraisHF.php';
        break;
     case "corrigerFraisForfait":
        $idVisiteur = filter_input(INPUT_POST, 'hdIdNom', FILTER_SANITIZE_STRING);
        $nomPrenom = $pdo->getNomVisiteur($idVisiteur);
        $moisFiche = filter_input(INPUT_POST, 'hdMois', FILTER_SANITIZE_STRING);
        $lesFrais = filter_input(INPUT_POST, 'txtlesFrais', FILTER_DEFAULT,
            FILTER_FORCE_ARRAY);
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $moisFiche, $lesFrais);
            ajouterInfo(
                'La modification des frais forfaitisés à été prise en compte ! ');
            include 'vues/v_infos.php';
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        ;
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $moisFiche);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,
            $moisFiche);
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $moisFiche);
        include 'vues/v_listeVisiteurs.php';
        include 'vues/v_validerFrais.php';
        include 'vues/v_validerFraisHF.php';
        break;
    case "corrigerFraisHF":
        $idVisiteur = filter_input(INPUT_POST, 'hdIdNom', FILTER_SANITIZE_STRING);
        $nomPrenom = $pdo->getNomVisiteur($idVisiteur);
        $moisFiche = filter_input(INPUT_POST, 'hdMois', FILTER_SANITIZE_STRING);
        $idFiche = filter_input(INPUT_POST, 'hdIdFiche', FILTER_SANITIZE_STRING);
        $dateFrais = dateAnglaisVersFrancais(
            filter_input(INPUT_POST, 'dateHFdate', FILTER_SANITIZE_STRING));
        $libelle = filter_input(INPUT_POST, 'txtHFlibelle',
            FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'txtHFmontant',
            FILTER_VALIDATE_FLOAT);
        if (nbErreurs() != 0) {
            include 'vues/v_erreurs.php';
        } else {
            if (substr($libelle, 0, 6) == "REPORT") {
                // traitement spécifique : suppression mois actuel + insertion mois suivant
                $lemois = $pdo->reporteFraisHorsForfait($idVisiteur, $libelle,
                    $dateFrais, $montant, $idFiche);
                ajouterInfo("Report de la ligne sur la fiche " . $lemois . ".");
            } else {
                $pdo->majFraisHorsForfait($idVisiteur, $moisFiche, $libelle,
                    $dateFrais, $montant, $idFiche);
                ajouterInfo(
                    "La modification demandée a été effectuée pour '" . $libelle .
                    "' (" . $montant . "€) à la date du " . $dateFrais);
            }
            include 'vues/v_info.php';
        }
        ;
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $moisFiche);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,
            $moisFiche);
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $moisFiche);
        include 'vues/v_listeVisiteurs.php';
        include 'vues/v_validerFrais.php';
        include 'vues/v_validerFraisHF.php';
        break;

    case "validerFiche":
        $nbJustificatifs = filter_input(INPUT_POST, 'txtNbJustificatifs',
            FILTER_SANITIZE_STRING);
        $idVisiteur = filter_input(INPUT_POST, 'hdIdNom', FILTER_SANITIZE_STRING);
        $moisFiche = filter_input(INPUT_POST, 'hdMois', FILTER_SANITIZE_STRING);
        $pdo->majNbJustificatifs($idVisiteur, $moisFiche, $nbJustificatifs);
        $pdo->valideSommeFrais($idVisiteur, $moisFiche);
        $pdo->majEtatFicheFrais($idVisiteur, $moisFiche, "VA");
        ajouterInfo("La validation a été effectuée !");
        include 'vues/v_info.php';
        header('Location: index.php');
        break;
    default:
        include 'vues/v_accueil_comptable.inc.php';
}
        
    