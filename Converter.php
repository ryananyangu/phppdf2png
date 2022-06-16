<?php
include_once __DIR__ . '/vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Extract;

// Configuration setup
Configuration::instance([
    'cloud' => [
        'cloud_name' => getenv("CLOUDINARY_NAME"),
        'api_key' => getenv("CLOUDINARY_API_KEY"),
        'api_secret' => getenv("CLOUDINARY_API_SECRET")
    ],
    'url' => [
        'secure' => true
    ]
]);

// initialize cloudinary
$cld = new Cloudinary();

// $_POST['']

// Convert the page into image
$response = (new ImageTag('multi_page_pdf.jpg'))
    ->resize(Resize::fill()->width(200)->height(250))
    ->extract(Extract::getPage()->byNumber(2));
