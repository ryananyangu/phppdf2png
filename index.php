<?php
include_once __DIR__ . '/vendor/autoload.php';

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;


// Confirm values for page number to be extracted and the file selection are okay
if (isset($_POST['page_no']) && isset($_FILES['upload_file'])){

    // Cloudinary instance setup globaly accros the whole application
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

    // The the temp file storage location
    $file_tmp= $_FILES['upload_file']['tmp_name'];

    // get the file contents
    $data = file_get_contents($file_tmp);

    // base64 encode the file contents
    $base64 = 'data:application/pdf;base64,' . base64_encode($data);


    // Upload the file as png from pdf
    $resp = (new UploadApi())->upload($base64, [
		'resource_type' => 'image',
        'format'=> 'png', // for the transformation
        'async' => false, // api to return the final status within the same session
        'type' => 'upload', 
		]
	);

}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP PDF to Image</title>
 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <meta name="theme-color" content="#7952b3">
 
  </head>
  <body>
    
 
<div class="container py-3">
  <header>
    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <h3 class="display-4 fw-normal">Php pdf to Image</h3>
    </div>
  </header>
 
  <main>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-4 offset-4">
                <label  class="sr-only">Upload Pdf</label>
                <input type="file" class="form-control" name="upload_file" placeholder="Select pdf file">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4 offset-4">
                <label  class="sr-only">Page number</label>
                <input type="text" class="form-control" name="page_no" placeholder="Page number to convert" min="1">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4 offset-4">
                <button type="submit" class="btn btn btn-dark">Convert to Image</button>
            </div>
        </div>
    </form>
 
    <div class="col">
        <?php if(isset($_FILES['upload_file'])){ ?>
            <img class="img-fluid" src="<?=$resp['url']?>" alt="response">
        <?php }else { ?>
            <h2 class="display-6 text-center mb-4">Upload pdf</h2>
        <?php  } ?>
    </div>
  </main>
</div>
  </body>
</html>