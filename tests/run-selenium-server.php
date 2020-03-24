<?php
define('STDIN', fopen('php://stdin', 'r'));

$thisPath = dirname(__FILE__).'\\selenium-server\\';
$batFile = 'run-selenium-server.bat';
$seleniumURL = 'http://selenium-release.storage.googleapis.com/3.141/selenium-server-standalone-3.141.59.jar';
$chromeDriverURL = 'https://chromedriver.storage.googleapis.com/80.0.3987.106/chromedriver_win32.zip';

if (!file_exists($thisPath))
    mkdir($thisPath, 0777, true);


$seleniumBase = basename($seleniumURL);
if (!file_exists($thisPath.$seleniumBase)){
    echo "Download $seleniumBase...\n";
    file_put_contents($thisPath.$seleniumBase, fopen($seleniumURL, 'r'));
}

$chromeDriverBase = basename($chromeDriverURL);
if (!file_exists($thisPath.'chromedriver.exe')) {
    echo "Download $chromeDriverBase...\n";
    file_put_contents($thisPath . $chromeDriverBase, fopen($chromeDriverURL, 'r'));

    echo "Unzip $chromeDriverBase...\n";
    $zip = new ZipArchive;
    $zip->open($thisPath.$chromeDriverBase);
    $zip->extractTo($thisPath);
    $zip->close();

    unlink($thisPath.$chromeDriverBase);
}

echo "Create $batFile\n";
file_put_contents($thisPath.$batFile, "java -jar -Dwebdriver.chrome.driver={$thisPath}chromedriver.exe {$thisPath}{$seleniumBase}");

system("java -jar -Dwebdriver.chrome.driver={$thisPath}chromedriver.exe {$thisPath}{$seleniumBase}");