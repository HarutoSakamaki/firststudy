<?php

    
    require_once './vendor/autoload.php';
    // importing settings via namespace
    use PhpOffice\PhpWord\Settings;

    // calling settings right above file generation. (As stated in this answer)
    Settings::setZipClass(Settings::PCLZIP);

    use PhpOffice\TemplateProcessor;
    

    

    $nowdate = microtime();
    $filename = 'word/'.$nowdate.'.docx';

    $tempname = new TemplateProcessor('word/phpwordtemplate.docx');
    

    $tempname -> saveAs($filename);
    



    echo 'こんにちは世界'; 
?>