<?php

$app = new Silex\Application();

// anonymous sessions
$app->register(new Silex\Provider\SessionServiceProvider());

// register imagine service provider
$app->register(new Neutron\Silex\Provider\ImagineServiceProvider());

// symfony url generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// set debug mode on
$app['debug'] = true;

// set upload folder
$app['upload_folder']= __DIR__ . '/../uploads';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));