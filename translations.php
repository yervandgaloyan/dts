<?php

include_once __DIR__.'/core/language.php';
include_once __DIR__.'/core/translate.php';

$selectedLanguage = '';
$translations = array();
$language = new Language();
$translate = new Translate();

function setFileName(string $fileName = null) : int
{
    if(is_null($fileName)) return -1;
    
    global $selectedLanguage, $translations, $language, $translate;

    $selectedLanguage = $language->getSelectedLanguage();     
    $translations = $translate->getTranslationsFromDB(
        pathinfo($fileName, PATHINFO_FILENAME), 
        $selectedLanguage
    );
    return 1;
}

function setLanguage(string $lang = null) : int
{
    global $language;

    return $language->setLanguage($lang);
}

function getAvailableLanguages() : array
{
    global $language;

    return $language->getAvailableLanguages();
}

function t(string $keyName = null) : string
{
    global $selectedLanguage, $translations;
    return array_key_exists($keyName, $translations) ? $translations[$keyName] : $keyName;

}
// setFileName('index.php');
// echo t('sjsh');