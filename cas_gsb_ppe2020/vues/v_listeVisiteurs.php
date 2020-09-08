<?php
/**
 * Vue de la liste des visiteurs et des mois des fiches à valider
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

<!DOCTYPE html>
<!--


    <div class="row">
	<div class="col-md-4">
		<h5>Sélectionner un visiteur :</h5>
	</div>
	<div class="col-md-4">
		<form id="formulaire"
			action="index.php?uc=validerFrais&action=voirListeFrais" method="post">
			<div class="form-group">
				<select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
					<option value="" disabled selected>...</option>
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $unId = $unVisiteur['id'];
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                        ?>
                         <option value="<?php

                        echo $unId?>">
                         <?php

                        echo $nom . ' ' . $prenom?>
                         </option>
                    <?php
                    }
                    ?>
                </select> <select id="lstMois" name="lstMois"
					class="form-control">
					<option value="" disabled selected>...</option>
                    <?php
                    foreach ($TouslesMois as $unMois) {
                        $unID = $unMois['ID'];
                        $leMois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        ?>
                        <option id=<?php

                        echo $unID?> value="<?php

                        echo $leMois?>">
                        <?php

                        echo $numMois . '/' . $numAnnee?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
                            <br><br>
                            <button class="btn btn-success" type="submit" id="valider" >Valider</button>
                           
                                
			</div>

	</div>
	</form>
</div>