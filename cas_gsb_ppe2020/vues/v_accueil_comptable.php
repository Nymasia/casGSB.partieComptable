<?php
/**
 * Vue Accueil des comptables
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
?>
<div id="accueil">
    <h2>
        Gestion des frais<small> - Comptable : 
            <?php 
            echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
            ?></small>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-bookmark"></span>
                    Navigation
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <a href="index.php?uc=validerFrais&action=selectionnerUtilisateur"
                           class="btn btn-warning btn-lg" role="button">
                            <span class="glyphicon glyphicon-ok"></span>
                            <br>Valider les fiche de frais</a>
                        <a href="index.php?uc=suiviFrais&action=afficherSuivi"
                           class="btn btn-warning btn-lg" role="button">
                            <span class="glyphicon glyphicon-euro"></span>
                            <br>Afficher mes fiches de frais</a>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>