<?php
declare(strict_types=1);

function getTransactionFiles(string $dirPath): array{
    $file = [];

    foreach(scandir($dirPath) as $file){
         $fullPath = $dirPath . DIRECTORY_SEPARATOR . $file; 

        if(is_dir($fullPath)){
            continue;
        }
       $files[] = $fullPath;
    }


    return $files;
}

function getTransaction(string $fileName , ?callable $transactionHandler = null) : array{
    if(! file_exists($fileName)){
        trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR);
    }

    $file = fopen($fileName, 'r');

    fgetcsv($file);

    $transactions = [];

    while(($transaction = fgetcsv($file ))!== false){
        if($transactionHandler !== null){
            $transaction = $transactionHandler($transaction);
        }
    $transactions[] = $transaction;
    }

    return $transactions;
}

function extractTransaction(array $transactionRow): array{
[$date, $checkNumber, $description, $amount] = $transactionRow;

$amount = (float) str_replace(['$', ','], '', $amount);

return [
    'date' => $date,
    'checkNumber' => $checkNumber,
    'description' => $description,
    'amount' => $amount
];
}

function calculateTotals(array $transactions): array{
    $total = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

    foreach ($transactions as $transaction){
        $total['netTotal'] += $transaction['amount'];

        if($transaction['amount'] >= 0){
            $total['totalIncome'] += $transaction['amount'];
        }else{
            $total['totalExpense'] += $transaction['amount'];
        }
    }

    return $total;
}