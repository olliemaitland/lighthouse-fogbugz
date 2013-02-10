<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new LightFogz\Application();

$app['debug'] = true;

require __DIR__.'/resources/config/prod.php';
require __DIR__.'/src/app.php';

use Knp\Provider\ConsoleServiceProvider;

$app->register(new ConsoleServiceProvider(), array(
    'console.name'              => 'Lighthouse to Fogbugz',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__.'/..'
));


$application = $app['console'];
$application->add(new LightFogz\Console\Command\SetupLighthouseCommand());
$application->add(new LightFogz\Console\Command\PushTicketsCommand());
$application->run();

$app->run();