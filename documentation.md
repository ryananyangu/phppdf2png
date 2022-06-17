### PDF to Image Conversion using Php

## Introduction
There are numerous PDF readers, and applications accessible for free on the internet that can help you open and view PDF document files. But, to really make changes to a PDF, you need something more advanced. This article demonstrates a PDF converter tool to convert PDF to image and how Cloudinary can be vital to achieving such a process.

## Codesandbox

Check the sandbox demo on  [Codesandbox](/).

<CodeSandbox
title=""
id=" "
/>

You can also get the project GitHub repo using [Github](/).

## Prerequisites

Entry-level html and php knowledge.

## Setting Up the Sample Project

Create a new folder: `phppdf2png`
Inside the new folder's terminal use the command `componser init`. You need to have `php` and `composer` downloaded to your machine.

Follow the composer procedure which will help hand'e the necessary project dependancies. When asked to search for a package, serch for `clouidinary`


## Cloudinary
[Cloudinary](https://cloudinary.com/?ap=em) reffers an end-to-end image and video-management solution for websites and mobile apps, covering everything from image and video uploads, storage, manipulations, optimizations to delivery.Our app will use the media file online upload feature.
To begin, click [here](https://cloudinary.com/console) to set up a new account or log into an existing one. We use the environment keys from the user dashboard to intergrate Cloudinary with our project. We will create a file named `env` and use the guide below to fill in the project configuration.

```bash
      CLOUDINARY_NAME=
      CLOUDINARY_API_KEY=
      CLOUDINARY_API_SECRET=
      GOOGLE_API_KE=
```
Our app will include 2 sections; `html` and `php`. Start by creating a directory `index.php` and including the following:

```php
"index.php"


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
```

Above, we create a html web page titled `PHP PDF to Image` and import bootstrap for the UI styling. It will have a header, an input tag to upload pdf files, a form to insert the page number to convert and a button to activate the conversion. The picture will also be available on a user's Cloudinary media library.The result will look like below:

![UI](https://res.cloudinary.com/dogjmmett/image/upload/v1655442787/UI_on0fx1.png "UI")

Now to the php section:

Start by importing cloudinary.

```php

include_once __DIR__ . '/vendor/autoload.php';

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
```

Confirm the post request has all the required parameters with values

```php

if (isset($_POST['page_no']) && isset($_FILES['upload_file'])){

}
```
Instanciate Cloudinary sdk instance with credentials from env

```php
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
```

Extract the temporary file location for the uploaded file

```php

$file_tmp= $_FILES['upload_file']['tmp_name'];

```
Use the file location earlier extracter to generate the base64 encoded string equivalent for the
uploaded file.

```php

$file_tmp= $_FILES['upload_file']['tmp_name'];

$data = file_get_contents($file_tmp);
$base64 = 'data:application/pdf;base64,' . base64_encode($data);

```

Upload the base64 string to cloudinary and apply transformation to convert the 
file to image png.
Additional options have been explained using the comments on the code snippet

```php


$resp = (new UploadApi())->upload($base64, [
		'resource_type' => 'image',
        'format'=> 'png', // for the transformation
        'async' => false, // api to return the final status within the same session
        'type' => 'upload', 
	])

```

Thats it! Your finel code should look like below:

```php

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
```

You've successfully created a pdf to image php converter. Enjoy the experience