<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * "root"
 */
$app->get("/", function () use ($app) {
    $twigvars = array();

    // check if image exists in session
    if ($image = $app['session']->get('image')) {
      $twigvars['image_path'] = 'uploads/' . $image;
    }

    $twigvars['imagine_presets'] = array('vintage' => 'Vintage',
                                         'lomo' => 'Lomo',
                                         'clarity' => 'Clarity',
                                         'sunrise' => 'Sunrise',
                                         'crossProcess' => 'Cross Process',
                                         'orangePeel' => 'Orange Peel',
                                         'love' => 'Love',
                                         'grungy' => 'Grungy',
                                         'jarques' => 'Jarques',
                                         'pinhole' => 'PinHole',
                                         'oldBoot' => 'Old Boot',
                                         'glowingSun' => 'Glowing Sun',
                                         'hazyDays' => 'Hazy Days',
                                         'herMajesty' => 'Her Majesty',
                                         'nostalgia' => 'Nostalgia',
                                         'hemingway' => 'Hemingway',
                                         'concentrate' => 'Concentrate');

    $twigvars['site_base_url'] = $app['url_generator']->generate('homepage', array(), true);
    return $app['twig']->render('index.twig', $twigvars);
})->bind('homepage');;

/**
 * Handle posts
 */
$app->post('/', function(Request $request) use ($app) {
    $twigvars = array();
    $file_bag = $request->files;
    if ($file_bag->has('image')) {
        $image = $file_bag->get('image');
        if ($image->getError()) {
          $twigvars['error'] = 'File upload error, check you max file upload size.';
          return $app['twig']->render('index.twig', $twigvars);
        }

        $image_loaded = $app['imagine']->open($image->getPathName());

        // get image informations using Imagine
        $image_box = $image_loaded->getSize();
        $image_width = $image_box->getWidth();
        $image_height = $image_box->getHeight();

        // support image upper 639px
        if ($image_box->getWidth() < 639) {
          $twigvars['error'] = 'Image width not supported.';
          return $app['twig']->render('index.twig', $twigvars);
        }

        // create image name
        $temp_name = tempnam($app['upload_folder'], 'instaclone_image_');

        // unlink we need just the filename
        unlink($temp_name);

        // add extension to filename
        $temp_name .= '.jpg';

        // save resized image keeping aspect ratio
        // THUMBNAIL_INSET: http://imagine.readthedocs.org/en/v0.2.1/image.html
        $image_loaded->thumbnail(
                        new Imagine\Image\Box(1024, 768),
                        Imagine\Image\ImageInterface::THUMBNAIL_INSET)
                      ->save($temp_name);

        // save filename name on user session
        $app['session']->set('image', basename($temp_name));
    }

    // redirect to hp
    $url = $request->getUriForPath('/');
    return $app->redirect($url);
});
