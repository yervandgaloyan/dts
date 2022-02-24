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
        if(!file_exists(__dir__ . '/../' . $this->translationsFolder . $language . '/' . $fileName . $this->translationFileType)) return array(); 
        $json = json_decode(file_get_contents(__dir__ . '/../' . $this->translationsFolder . $language . '/'. $fileName . $this->translationFileType), true);
        return $json;
    }

    public function setTranslationsToDB(string $fileName = null, array $translations = null, string $language = null) : int 
    {
        if(is_null($fileName) || is_null($translations) || is_null($language)) return -1;
        
        if (!file_exists(__dir__ . '/../' . $this->translationsFolder . $language)) mkdir(__dir__ . '/../' . $this->translationsFolder . $language, 0755, true);
        $translations += array('lastUpdate' => time());

        file_put_contents(__dir__ . '/../' . $this->translationsFolder . $language . '/'. $fileName . $this->translationFileType, json_encode($translations));
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
        if(!in_array($fileName, $translatedFiles)) {
            array_push($translatedFiles, $fileName);
            parent::setConfig('translatedFiles', $translatedFiles);
        }
        $availableLanguages = parent::getAvailableLanguages();
        $translationKeys = $this->getTranslationKeysFromFile($fileName);
        foreach ($availableLanguages as $key => $value) 
        {
            $this->setTranslationsToDB(
                pathinfo($fileName, PATHINFO_FILENAME),
                $this->compareTranslationsWithDB(
                    $translationKeys == 0 ? array() : $translationKeys,
                    $this->getTranslationsFromDB(pathinfo(
                        $fileName, PATHINFO_FILENAME), 
                        $key
                    )
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
            if(file_exists(__dir__ . '/../' . $this->translationsFolder . $key . '/' .pathinfo($fileName, PATHINFO_FILENAME) . $this->translationFileType)) 
            {
                unlink(__dir__ . '/../' . $this->translationsFolder . $key . '/' . pathinfo($fileName, PATHINFO_FILENAME) . $this->translationFileType);
            }
        }
        array_splice($translatedFiles, array_search($fileName, $translatedFiles), 1);
        
        parent::setConfig('translatedFiles', $translatedFiles);
        return 1;    
    }    
    
    public function getTranslatedFiles(){
        return parent::getConfigByName('translatedFiles');
    }

    public function updateLanguageFiles() : int{
        foreach ($this->getTranslatedFiles() as $key => $value) {
            $this->addTranslatedFile($value);
        }  
        return 1;
    }

    public function updateAndGetTranslationFile($fileName, $langCode){
        $this->setTranslationsToDB(pathinfo($fileName, PATHINFO_FILENAME),$this->compareTranslationsWithDB($this->getTranslationKeysFromFile($fileName), $this->getTranslationsFromDB(pathinfo($fileName, PATHINFO_FILENAME), $langCode)), $langCode);
        return $this->getTranslationsFromDB(pathinfo($fileName, PATHINFO_FILENAME), $langCode);
    }

    public function getTranslationFileAllLanguages($fileName){
        $availableLanguages = parent::getAvailableLanguages();
        $translations = array();
        foreach ($availableLanguages as $key => $value) {
            
            $translations = array_merge($translations, array($key => $this->updateAndGetTranslationFile($fileName, $key)));
        }
        return $translations;
    }

    public function updateTranslationByKey($fileName, $langCode, $key, $value){
        $trans = $this->getTranslationsFromDB(pathinfo($fileName, PATHINFO_FILENAME), $langCode);
        $trans[$key] = $value;
        $this->setTranslationsToDB(pathinfo($fileName, PATHINFO_FILENAME), $trans, $langCode);
        return 1;
    }
}

// $tr = new Translate;
// $trans = $tr->getTranslationsFromDB(pathinfo('../test.php', PATHINFO_FILENAME), 'en');
// print_r($trans);
// $trans['a1'] = 'AAAAAA1';
// print_r($trans);
// $tr->setTranslationsToDB(pathinfo('../test.php', PATHINFO_FILENAME), $trans, 'en');
// print_r($tr->getTranslationsFromDB(pathinfo('../test.php', PATHINFO_FILENAME), 'en'));
// print_r($tr->getTranslationFileAllLanguages('../test.php'));
// $tr->addTranslatedFile('../test.php');
// $tr->removeTranslatedFile('ddsd.php');
// // $translate->setTranslationsToDB('index.json', $translate->getTranslationKeysFromFile('index.php'));
// $fileName = '../test.php';
// $tr->setTranslationsToDB(pathinfo($fileName, PATHINFO_FILENAME),$tr->compareTranslationsWithDB($tr->getTranslationKeysFromFile($fileName), $tr->getTranslationsFromDB(pathinfo($fileName, PATHINFO_FILENAME), 'ru')), 'ru');
// $re = '/t\\((?:\'|")[a-zA-Z0-9_-]*(?:\'|")\\)/i';
// $str = file_get_contents('index.php');
// var_dump( $str);

// print_r($tr->getTranslatedFiles());

// foreach ($tr->getTranslatedFiles() as $key => $value) {
//     $tr->addTranslatedFile($value);
// }

// print_r($tr->updateAndGetTranslationFile('../test.php', 'hy'));