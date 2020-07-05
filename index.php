<?php
declare(strict_types=1);

use ApiDocBuilder\Builder\Builder;

include 'vendor/autoload.php';
include 'config.php';

$builder = new Builder(ROOT . '/templates', ROOT . '/cache');
$builder->setVersion($version);

// add all tags to the yaml file.
foreach ($tags as $name => $description) {
    $builder->addTag($name, $description);
}

// add all paths to the final YAML file by simply looping the directory:
$files = scandir(ROOT . '/yaml/paths', SCANDIR_SORT_ASCENDING);
foreach ($files as $file) {
    if (!\in_array($file, ['.', '..','.DS_Store'])) {
        if ('yaml' === substr($file, -4)) {
            echo sprintf("Add %s\n", $file);
            $builder->addYamlFile('paths', ROOT . '/yaml/paths/' . $file, 1);
        }
    }
}

// add all models to the final YAML file by also looping the directory:

$files = scandir('./yaml/schemas', SCANDIR_SORT_ASCENDING);
foreach ($files as $file) {
    if (!\in_array($file, ['.', '..','.DS_Store'])) {
        echo sprintf("Add %s\n", $file);
        $builder->addYamlFile('schemas', ROOT . '/yaml/schemas/' . $file, 2);
    }
}


$result = $builder->render();

// put result in file:
$finalDestination = sprintf('%s/firefly-iii-%s.yaml', $destination, $version);

file_put_contents($finalDestination, $result);
echo "\nDone\n";


