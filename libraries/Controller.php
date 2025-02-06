<?php

    //Permet de charger les models et views
    class Controller {
        public function model($model) {
            //Requiert le fichier model 
            require_once (ROOT.'models/' . $model . '.php');
            //Instancie model
            return new $model();
        }

        //Permet de charger les vues (vérifie si elle existe)
        public function view($view, $data = []) {
            if (file_exists(ROOT.'views/' . $view . '.php')) {
                require_once(ROOT.'views/' . $view . '.php');
            } else {
                die("404, la page n'existe pas.");
            }
        }
    }