<?php
$svgFile = __DIR__.'/test3.svg';
$svgContent = file_get_contents($svgFile);
$xml = simplexml_load_string($svgContent);

foreach ($xml->children() as $path) {
    //var_dump($path['stroke']);
    $path['id'] = 'new-custom-id'; //This wil alter the path attributes to put the real lote data
}

// Guardar el contenido modificado en un nuevo archivo SVG
$modifiedSvgFile = __DIR__.'/nuevo_archivo.svg';
file_put_contents($modifiedSvgFile, $xml->asXML());
