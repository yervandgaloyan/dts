<?php

include_once 'language.php';

class Translate extends Language
{
    public function __construct() {
        $this->translationsFolder = parent::getConfigByName('translationsFolder');
        $this->translationFileType = parent::getConfigByName('translationFileType');

    }
    // Get translation keys from file
    public function getTranslationKeysFromFile(string $fileName = null)
    {
        if(is_null($fileName)) return -1;
        if(!file_exists($fileName)) return 0;
        $regexp =  '/t\\((?:\'|")[a-zA-Z0-9_-]*(?:\'|")\\)/i';

        preg_match_all($regexp, file_get_contents($fileName), $keys, PREG_PATTERN_ORDER);
        $keys = $keys[0];
        foreach($keys as &$value){
            $value = substr($value, 3, -2);
        }
        $keys = array_unique($keys);
        return $keys;
    }
    
    public function getTranslationsFromDB(string $fileName = null, string $language = null)
    {
        if(is_null($fileName) || is_null($language)) return -1;
        if(!file_exists($this->translationsFolder . $language . '/' . $fileName . $this->translationFileType)) return array(); 
        $json = json_decode(file_get_contents($this->translationsFolder . $language . '/'. $fileName . $this->translationFileType), true);
        return $json;
    }

    public function setTranslationsToDB(string $fileName = null, array $translations = null, string $language = null) : int 
    {
        if(is_null($fileName) || is_null($translations) || is_null($language)) return -1;
        
        if (!file_exists($this->translationsFolder . $language)) mkdir($this->translationsFolder . $language, 0755);
        $translations += array('lastUpdate' => time());

        file_put_contents($this->translationsFolder . $language . '/'. $fileName . $this->translationFileType, json_encode($translations));
        return 1;
    }

    public function compareTranslationsWithDB(array $translations = null, array $db = null)
    {
        if(is_null($translations) || is_null($db)) return -1;
        $newTranslations = array();
        foreach($translations as $value)
        {
            $newTranslations += array($value => array_key_exists($value, $db) ? $db[$value] : '');
            
        }
        return $newTranslations;
    }

    public function addTranslatedFile(string $fileName = null) : int
    {
        if(is_null($fileName)) return -1;
        $translatedFiles = parent::getConfigByName('translatedFiles');
        if(in_array($fileName, $translatedFiles)) return 0;

        array_push($translatedFiles, $fileName);
        parent::setConfig('translatedFiles', $translatedFiles);
        
        $availableLanguages = parent::getAvailableLanguages();
        foreach ($availableLanguages as $key => $value) 
        {
            $this->setTranslationsToDB(
                pathinfo($fileName, PATHINFO_FILENAME),
                $this->compareTranslationsWithDB(
                    $this->getTranslationKeysFromFile($fileName),
                    array()
                ), 
                $key 
            );
        }
        
        return 1;
    }    

    public function removeTranslatedFile(string $fileName = null) : int
    {
        if(is_null($fileName)) return -1;
        $translatedFiles = parent::getConfigByName('translatedFiles');
        if(!in_array($fileName, $translatedFiles)) return 0;
        
        $availableLanguages = parent::getAvailableLanguages();
        foreach ($availableLanguages as $key => $value) 
        {
            if(file_exists($this->translationsFolder . $key . '/' .pathinfo($fileName, PATHINFO_FILENAME) . $this->translationFileType)) 
            {
                unlink($this->translationsFolder . $key . '/' . pathinfo($fileName, PATHINFO_FILENAME) . $this->translationFileType);
            }
        }
        unset($translatedFiles[array_search($fileName, $translatedFiles)]);

        print_r($translatedFiles);
        
        parent::setConfig('translatedFiles', $translatedFiles);
        return 1;    
    }    
}

$tr = new Translate;
// $tr->addTranslatedFile('index.php');
$tr->removeTranslatedFile('index.php');
// // $translate->setTranslationsToDB('index.json', $translate->getTranslationKeysFromFile('index.php'));
// $tr->setTranslationsToDB('index',$tr->compareTranslationsWithDB($tr->getTranslationKeysFromFile('index.php'), $tr->getTranslationsFromDB('index', 'en')), 'en');
// $re = '/t\\((?:\'|")[a-zA-Z0-9_-]*(?:\'|")\\)/i';
// $str = file_get_contents('index.php');
// var_dump( $str);

