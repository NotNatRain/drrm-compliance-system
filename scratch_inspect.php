<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function parsePersonName(string $fullName): array
{
    $fullName = trim($fullName);
    if ($fullName === '') {
        return ['first_name' => '', 'middle_name' => '', 'last_name' => '', 'name_ext' => ''];
    }

    $extensions = ['JR', 'JR.', 'SR', 'SR.', 'II', 'III', 'IV', 'V', 'VI'];
    $surnamePrefixes = ['SAN', 'SANTA', 'STA', 'STA.', 'STO', 'STO.', 'DE', 'DEL', 'DELA', 'DE LA', 'DELOS', 'DE LOS', 'DELAS', 'DE LAS', 'VAN', 'VON', 'MC', 'MAC'];

    $nameExt = '';

    if (str_contains($fullName, ',')) {
        $parts = explode(',', $fullName, 2);
        $lastName = trim($parts[0]);
        $remainder = trim($parts[1]);

        $remainderWords = preg_split('/\s+/', $remainder);
        if (count($remainderWords) > 1 && in_array(strtoupper(end($remainderWords)), $extensions, true)) {
            $nameExt = array_pop($remainderWords);
        }

        $firstName = array_shift($remainderWords) ?? '';
        $middleName = implode(' ', $remainderWords);

        return [
            'first_name'  => $firstName,
            'middle_name' => $middleName,
            'last_name'   => $lastName,
            'name_ext'    => $nameExt,
        ];
    }

    $words = preg_split('/\s+/', $fullName);
    if (count($words) === 1) {
        return ['first_name' => $words[0], 'middle_name' => '', 'last_name' => '', 'name_ext' => ''];
    }

    if (in_array(strtoupper(end($words)), $extensions, true)) {
        $nameExt = array_pop($words);
    }

    if (count($words) === 1) {
        return ['first_name' => $words[0], 'middle_name' => '', 'last_name' => '', 'name_ext' => $nameExt];
    }

    if (count($words) === 2) {
        return [
            'first_name'  => $words[0],
            'middle_name' => '',
            'last_name'   => $words[1],
            'name_ext'    => $nameExt,
        ];
    }

    $secondToLast = strtoupper($words[count($words) - 2]);
    if (in_array($secondToLast, $surnamePrefixes, true)) {
        $lastName = $words[count($words) - 2] . ' ' . $words[count($words) - 1];
        array_splice($words, count($words) - 2, 2);
        $firstName = array_shift($words) ?? '';
        $middleName = implode(' ', $words);

        return [
            'first_name'  => $firstName,
            'middle_name' => $middleName,
            'last_name'   => $lastName,
            'name_ext'    => $nameExt,
        ];
    }

    $lastName = array_pop($words);
    $firstName = array_shift($words);
    $middleName = implode(' ', $words);

    return [
        'first_name'  => $firstName,
        'middle_name' => $middleName,
        'last_name'   => $lastName,
        'name_ext'    => $nameExt,
    ];
}

$testNames = [
    'Ramon San Juan',
    'Caleb Xia',
    'Rain Crisostomo',
    'mars salvador',
    'mona lisa',
    'Juan Santos Dela Cruz Jr.',
    'San Juan, Ramon',
    'Dela Cruz, Juan Santos Jr.',
];

foreach ($testNames as $name) {
    $res = parsePersonName($name);
    echo "Input: '$name' => First: '{$res['first_name']}', Middle: '{$res['middle_name']}', Last: '{$res['last_name']}', Ext: '{$res['name_ext']}'\n";
}
