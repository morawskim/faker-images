Faker Images Provider
====================

[picsum.photos](https://picsum.photos/) provider for [Faker](https://github.com/FakerPHP/Faker).

[lorem.space](https://lorem.space/) provider for [Faker](https://github.com/FakerPHP/Faker).

## Install
Install the Providers by adding `mmo/faker-images` to your composer.json or from CLI:

```
$ composer require --dev mmo/faker-images
```

## Usage

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$faker = Faker\Factory::create();
$faker->addProvider(new \Mmo\Faker\PicsumProvider($faker));
$faker->addProvider(new \Mmo\Faker\LoremSpaceProvider($faker));

// picsum
// size
$url = $faker->picsumUrl(); // https://picsum.photos/640/480?random=24398
$url = $faker->picsumUrl(400, 400); // https://picsum.photos/400/400?random=23446

// static random
$url = $faker->picsumStaticRandomUrl(400, 400); // https://picsum.photos/seed/5efe7fec1bd11/400/400

// download image to tmp dir
$path = $faker->picsum(null, 400, 400, true); // /tmp/72c04225dd87efc261d29d3a050aa9b6.jpg

// Signature
// picsumUrl($width = 640, $height = 480, $id = null, $randomize = true, $gray = false, $blur = null, $static = false, $imageExtension = null)
// picsumStaticRandomUrl($width = 640, $height = 480, $gray = false, $blur = null, $imageExtension)
// picsum($dir = null, $width = 640, $height = 480, $fullPath = true, $id = null, $randomize = true, $gray = false, $blur = null, $imageExtension)

// lorem space
$url = $faker->loremSpaceUrl(\Mmo\Faker\LoremSpaceProvider::CATEGORY_FACE); // https://api.lorem.space/image/face?w=640&h=480
// download image to tmp dir
$path = $faker->loremSpace(\Mmo\Faker\LoremSpaceProvider::CATEGORY_FACE); // /tmp/fd3646c544a9a46bd16d1d097e737ee4.jpg

// Signature
// loremSpaceUrl($category, $width = 640, $height = 480)
// loremSpace($category, $dir = null, $width = 640, $height = 480, $fullPath = true)
```
