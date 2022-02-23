<?php

    include_once('../core/translate.php');


    class TranslateApi extends Translate{
        
        // TODO add other endpoints & security checks

        public function __construct() {
            parent::__construct();
            if(isset($_GET['getTranslatedFiles'])){
                echo json_encode(parent::getTranslatedFiles());
                exit;
            }
            elseif (isset($_GET['updateLanguageFiles'])) {
                echo parent::updateLanguageFiles();
            }
            elseif(isset($_GET['addTranslatedFile']) && isset($_GET['fileName']))
            {
                echo parent::addTranslatedFile($_GET['fileName']);
                exit;
            }
            elseif (isset($_GET['removeTranslatedFile']) && isset($_GET['fileName']))
            {
                echo parent::removeTranslatedFile($_GET['fileName']);
                exit;
            }
        }
    }

    new TranslateApi();