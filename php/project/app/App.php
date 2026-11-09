<?php
declare(strict_types=1);

function getTransactionFiles(string $dirPath): array{
    $file = [];

    foreach(scandir(FILES_PATH) as $file){
         $fullPath = $dirPath . DIRECTORY_SEPARATOR . $file; 

        if(is_dir($fullPath)){
            continue;
        }
       $files[] = $fullPath;
    }


    return $files;
}

function getTransaction(string $fileName) : array{
    if(! file_exists($fileName)){
        trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR);
    }

    $file = fopen($fileName, 'r');

    $transactions = [];

    while(($transaction = fgetcsv($file ))!== false){
    $transactions[] = $transaction;
    }

    return $transactions;
}