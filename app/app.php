<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * "root"
 */
$app->get("/", function () use ($app) {
    $twigvars = array();
    $twigvars['title'] = 'Instaclone';

    // check if image exists in session
    if ($image = $app['session']->get('image')) {
      $image_path = 'uploads' . '/' . $image;
      $twigvars['image_path'] = $image_path . '.resized.jpg';
    }

    return $app['twig']->render('index.twig', $twigvars);
});

/**
 * Handle posts
 */
$app->post('/', function(Request $request) use ($app) {
    $file_bag = $request->files;

    if ($file_bag->has('image')) {
        $image = $file_bag->get('image');
        // check image size, we accept image higher than 639px
        list($width, $height) = getimagesize($image->getPathName());
        if ($width < 639) {
          $subRequest = Request::create('/');
          return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        }

        // save image
        $temp_name_path = tempnam($app['upload_folder'], 'img_') . '.jpg';
        $image->move(
          $app['upload_folder'],
          $temp_name_path
        );

        // resized
        $temp_name_resized = $temp_name_path . '.resized.jpg';
        $app['imagine']->open($temp_name_path)
                ->thumbnail(
                    new Imagine\Image\Box(640, 480),
                    Imagine\Image\ImageInterface::THUMBNAIL_INSET)
                ->save($temp_name_resized);

        // save filename name on user session
        $app['session']->set('image', basename($temp_name_path));
    }

    // redirect to hp
    $url = $request->getUriForPath('/');
    return $app->redirect($url);
});
