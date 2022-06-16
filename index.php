<?php
include_once __DIR__ . '/vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Extract;
use Cloudinary\Transformation\Format;

// Configuration setup
$config = Configuration::instance([
    'cloud' => [
        'cloud_name' => getenv("CLOUDINARY_NAME"),
        'api_key' => getenv("CLOUDINARY_API_KEY"),
        'api_secret' => getenv("CLOUDINARY_API_SECRET")
    ],
    'url' => [
        'secure' => true
    ]
]);

$cloudinary = new Cloudinary($config);

$upload_file = "";
if (isset($_POST['upload_file'])) {
    $upload_file = $_POST['upload_file'];
}


$page_no = "";
if (isset($_POST['page_no'])) {
    $page_no = $_POST['page_no'];
}
$img_url = (new ImageTag($upload_file))->extract(Extract::getPage()->byNumber($page_no))->delivery(Delivery::format("png"));
$resp = $img_url->upload($upload_file, [
    'overwrite' => true,
    'notification_url' => 'https://posthere.io/b897-4b59-abda',
    'resource_type' => 'image'
]);

$cloudinary->uploadApi();



// $img_url->upload()
print_r(json_encode(get_object_vars($resp)));
// fwrite(STDOUT, 'foo');


?>

<html>

<head>
    <meta charset="utf-8">
    <title>PDF to Image converter</title>
    <link rel="icon" href="https://phpsandbox.io/assets/img/brand/phpsandbox.png">
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;0,500;0,531;0,600;0,700;0,800;0,900;1,400;1,500;1,531;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <h2>Convert</h2>
    <form action="." method="POST">
        <input type="file" accept="application/pdf" name="upload_file" />
        <input type="number" placeholder="Page number" min="1" name="page_no" />
        <button type="submit">Convert</button>
    </form>

    <img src=<?php $img_url ?: "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" ?> alt="converted page image" />
    <div>
        <?php @readfile($upload_file ?: "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7") ?>
    </div>
</body>

</html>