<?php

include_once 'language.php';
include_once 'translate.php';

$selectedLanguage = '';
$translations = array();

function setFileName(string $fileName = null)
{
    if(is_null($fileName)) return -1;
    
    global $selectedLanguage, $translations;

    $selectedLanguage = new Language();
    $selectedLanguage = $selectedLanguage->getSelectedLanguage();

    $translations = new Translate(); 
    $translations = $translations->getTranslationsFromDB(pathinfo($fileName, PATHINFO_FILENAME), $selectedLanguage);
}

function t(string $keyName = null) : string
{
    global $selectedLanguage, $translations;
    if(is_null($keyName)) return '';
    return array_key_exists($keyName, $translations) ? $translations[$keyName] : '';

}
// setFileName('index.php');