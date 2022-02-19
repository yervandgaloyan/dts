<?php

    include_once('../core/settings.php');


    class SettingsApi extends Settings{
        
        // TODO add other endpoints & security checks

        public function __construct() {
            if(isset($_GET['getAllConfigs']))
            {
                echo json_encode(parent::getAllConfigs());
            }
            elseif (isset($_GET['setConfig']) && isset($_GET['confName']) && isset($_GET['confValue']))
            {
                json_decode($_GET['confValue']);
                if(json_last_error() === JSON_ERROR_NONE){
                    echo json_encode(parent::setConfig($_GET['confName'], json_decode($_GET['confValue'], true)));
                }else{
                    echo json_encode(parent::setConfig($_GET['confName'], $_GET['confValue']));
                }
            }
        }
    }

    new SettingsApi();