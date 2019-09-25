<?php

/*=======================Constantes==================*/

define( 'LC_POST_TYPE_CASE',  'case' );
define( 'LC_TAXO_GAMME',  'champ_explo' );



/*=======================Include==================*/

//Fichier de déclaration des custom post type
include_once('custom-content/set-custom-post-type.php');
//Fichier de paramétrage du BO
include_once('custom-content/custom-settings.php');
//Fichier de tri
include_once('custom-content/admin-filter-post.php');
