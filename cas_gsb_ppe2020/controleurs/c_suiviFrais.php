<?php
/**
 * Gestion du suivi des fiches de frais
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


// --------------------------------------------------------------
// Jeu de variables pour adaptation du comportement du programme
$balance = 214221.21; // balance du compte de paiement
$limiteVAVersMP = 20; // jour de mise en paiement
$limiteMPVersRB = 30; // jour (présumé) de remboursement
                      // --------------------------------------------------------------

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

$idVisiteur = filter_input(INPUT_POST, 'hdVisiteur', FILTER_SANITIZE_STRING);
$mois = filter_input(INPUT_POST, 'hdMois', FILTER_SANITIZE_STRING);

include 'vues/v_suiviFrais.php';
switch ($action) {
    case 'afficherSuivi':
        $pdo->mettreEnPaiementVAMoisPrecedent(20);
        $pdo->rembourserMPMoisPrecedent(30);
        break;
    case 'VAversMP':
        $pdo->majEtatFicheFrais($idVisiteur, $mois, 'MP');
        break;
    case 'MPversVA':
        $pdo->majEtatFicheFrais($idVisiteur, $mois, 'VA');
        break;
    case 'MPversRB':
        $pdo->majEtatFicheFrais($idVisiteur, $mois, 'RB');
        break;
    case 'VAversCL':
        $pdo->majEtatFicheFrais($idVisiteur, $mois, 'CL');
        break;
    default:
    // Pas de comportement spécifique en cas de mauvais paramètre passé
}
$lesfichesVA = $pdo->getLesFiches('VA');
$lesfichesMP = $pdo->getLesFiches('MP');
$lesfichesRB = $pdo->getLesFiches('RB');

// Déterminer les montants totaux concernés.
$montantVA = CompterMontantTotal($lesfichesVA);
$montantMP = CompterMontantTotal($lesfichesMP);
$montantRB = CompterMontantTotal($lesfichesRB);
$ficheaffichee = CompterfichesPerimees($lesfichesRB);
require 'vues/v_suiviFrais.php';