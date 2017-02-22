<?php
/**
 * Date: 22/02/2017
 * Time: 21:40
 */

$links = [
    'https://shop.hopburnsblack.co.uk/collections/belgium',
];

$contextOptions = array(
    'http' => array(
        'method' => 'GET',
        //'proxy' => 'tcp://proxy:6666'
    )
);

$context = stream_context_create($contextOptions);
$use_include_path = false;

$results = array();

foreach ($links as $link) {
    $content = file_get_contents($link, $use_include_path, $context);
    preg_match_all('/<p class="grid-link__title">([\s\S]*)<\/p>[\s\S]*<p class="grid-link__meta">([\s\S]*)<\/p>/iuU', $content, $match, PREG_SET_ORDER);
    foreach ($match as $m) {
        $results[] = array(
            'name' => trim($m[1]),
            'price' => trim($m[2])
        );
    }
}


// send data as csv

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

foreach ($results as $result) {
    fputcsv($output, $result);
}