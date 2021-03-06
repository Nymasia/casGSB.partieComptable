<?php
/**
 * Vue des inofrmations des fiches
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

<div class="alert alert-success" role="success">
    <?php
    foreach ($_REQUEST['infos'] as $info) {
        echo '<p>' . htmlspecialchars($info) . '</p>';
    }
    ?>
</div>