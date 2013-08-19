<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * "root"
 */
$app->get("/", function () use ($app) {
    $twigvars = array();
    $twigvars['title'] = "Instaclone app";
    $twigvars['content'] = "Body";
    return $app['twig']->render('index.twig', $twigvars);
});

/**
 * Handle posts
 */
$app->post('/', function(Request $request) use ($app) {
    $file_bag = $request->files;

    if ($file_bag->has('image')) {
        $image = $file_bag->get('image');
        $name = tempnam($app['upload_folder'],'img_');
        $image->move(
          $app['upload_folder'],
          $name
        );
        // save filename name on user session
        $app['session']->set('image', $name);
    }

    // This is just temporary.
    // Replace with a RedirectResponse to Gallery
    return print_r($app['session']->get('image'));
});
