<?php
// On génère une constante contenant le chemin vers la racine publique du projet
define('ROOT', str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));

define("WWW_ROOT", rtrim($_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/'));

// On appelle le modèle et le contrôleur principaux
   //Requiert les librairies génériques
  
require_once (ROOT.'libraries/Controller.php');
require_once (ROOT.'libraries/Database.php');

//require_once (ROOT.'helpers/session_helper.php');
//require_once (ROOT.'helpers/time_ago.php');

   // On sépare les paramètres et on les met dans le tableau $params
$params = explode('/', $_GET['p']);

// Si au moins 1 paramètre existe
if($params[0] != ""){
    // On sauvegarde le 1er paramètre dans $controller en mettant sa 1ère lettre en majuscule
    $controller = ucfirst($params[0]);

    // On sauvegarde le 2ème paramètre dans $action si il existe, sinon index
    $action = isset($params[1]) ? $params[1] : 'index';

    // On appelle le contrôleur
    require_once(ROOT.'controllers/'.$controller.'.php');

    // On instancie le contrôleur
    $controller = new $controller();

    if(method_exists($controller, $action)){
        unset($params[0]);
        unset($params[1]);
        call_user_func_array([$controller,$action], $params);
        // On appelle la méthode
        // $controller->$action();    
    }else{
        // On envoie le code réponse 404
        http_response_code(404);
        echo "La page recherchée n'existe pas";
    }
}else{
    // Ici aucun paramètre n'est défini
    // On appelle le contrôleur par défaut
    require_once(ROOT.'controllers/Pages.php');

    // On instancie le contrôleur
    $controller = new Pages();

    // On appelle la méthode index
    $controller->index();
}
