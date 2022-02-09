<?php

include_once "settings.php";

class Language extends Settings{

    // Return selected language
    public function getSelectedLanguage() : string{
        

        if(!isset($_COOKIE['language']))
            return parent::getConfigByName('defaultLanguage');
        return $_COOKIE[$cookie_name];
    }

    // Set language
    public function setLanguage(string $lang = null) : int{
        if(is_null($lang)) return -1;
        if(!$this->isLanguageAvailable($lang)) return 0;
        setcookie("language", $lang, 2147483647, "/");
        return 1;
    }

    // return all available languages
    public function getAvailableLanguages() : array{
        return parent::getConfigByName('availableLanguages');
    }

    // set available languages
    public function setAvailableLanguages(array $availableLanguages = null) : int{
        if(is_null($availableLanguages)) return -1;
        return parent::setConfig('availableLanguages', $availableLanguages) ? 1 : 0;
    }
    
    // Return 1 if available, 0 if not.
    function isLanguageAvailable(String $lang = null) : int{
        if(is_null($lang)) return -1;
        if(array_key_exists($lang, $this->getAvailableLanguages())) return 1;
        return 0;
    }
}
$lang = new Language;
print_r($lang->getAvailableLanguages());
// echo $lang->setLanguage("hy");
// $availableLanguages = $lang->getAvailableLanguages();
// $availableLanguages['en'] = 'ENGLESHEEEE';
// $lang->setAvailableLanguages($availableLanguages);
// echo $lang->isLanguageAvailable('edn');
?>