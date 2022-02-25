<?php

    include_once('../core/language.php');


    class LanguageApi extends Language{
        
        // TODO add other endpoints & security checks

        public function __construct() {
            if(isset($_GET['getSelectedLanguage']))
            {
                echo json_encode(parent::getSelectedLanguage());
            }
            elseif (isset($_GET['getAvailableLanguages']))
            {
                echo json_encode(parent::getAvailableLanguages());
            }elseif (isset($_GET['removeLanguage']) && isset($_GET['langCode']))
            {
                echo json_encode(parent::removeLanguage($_GET['langCode']));
                
            }elseif (isset($_GET['addLanguage']) && isset($_GET['langCode']) && isset($_GET['langName']))
            {
                echo json_encode(parent::addLanguage($_GET['langCode'],$_GET['langName']));
                
            }elseif (isset($_GET['getDefaultLanguage']))
            {
                echo parent::getDefaultLanguage();
                
            }elseif (isset($_GET['setDefaultLanguage']) && isset($_GET['langCode']))
            {
                echo json_encode(parent::setDefaultLanguage($_GET['langCode']));
                
            }
        }
    }

    new LanguageApi();