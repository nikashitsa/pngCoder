PngCoder (encoder/decoder)
==========================

[![Build Status](https://travis-ci.org/nikashitsa/pngCoder.svg?branch=master)](https://travis-ci.org/nikashitsa/pngCoder)

PngCoder is a single PHP class which can encode any file to PNG image and then decode it to original file. 

```php
$coder = new PngCoder();
// encode music file to image
$coder->encode('./data/music.mp3', './tmp/music.png');
// decode it
$coder->decode('./tmp/music.png', './tmp/music.mp3');
```

Sometimes encoded files looks mysterious, but in common case it's just a noise:

![Noise Image](https://github.com/nikashitsa/pngCoder/blob/master/data/crime_encoded.png?raw=true)

I don't know where this can be used :worried:. Feel free to add your suggestions [here](https://github.com/nikashitsa/pngCoder/issues) :wink:

## Requirements

- PHP 5.4 and later (supports PHP 7)
- php-gd

## Installing PngCoder

The recommended way to install PngCoder is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the PngCoder:

```bash
composer.phar require nikashitsa/png-coder
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```
