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
            elseif(isset($_GET['getTranslationFileAllLanguages']) && isset($_GET['fileName']))
            {
                echo json_encode(parent::getTranslationFileAllLanguages($_GET['fileName']));
                exit;
            }
            elseif (isset($_GET['removeTranslatedFile']) && isset($_GET['fileName']))
            {
                echo parent::removeTranslatedFile($_GET['fileName']);
                exit;
            }
            elseif (isset($_GET['updateTranslationByKey']) && isset($_GET['fileName']) && isset($_GET['langCode']) && isset($_GET['key']) && isset($_GET['value']))
            {
                echo parent::updateTranslationByKey($_GET['fileName'], $_GET['langCode'], $_GET['key'], $_GET['value']);
                exit;
            }
            
        }
    }

    new TranslateApi();