# miya/custom-field-map

[![Build Status](https://travis-ci.org/miya0001/custom-field-map.svg?branch=master)](https://travis-ci.org/miya0001/custom-field-map)
[![Latest Stable Version](https://poser.pugx.org/miya/custom-field-map/v/stable)](https://packagist.org/packages/miya/custom-field-map)
[![Total Downloads](https://poser.pugx.org/miya/custom-field-map/downloads)](https://packagist.org/packages/miya/custom-field-map)
[![Latest Unstable Version](https://poser.pugx.org/miya/custom-field-map/v/unstable)](https://packagist.org/packages/miya/custom-field-map)
[![License](https://poser.pugx.org/miya/custom-field-map/license)](https://packagist.org/packages/miya/custom-field-map)

Add a custom field to save latitude and longitude to the edit screen of the specific post type for WordPress.

![](https://www.evernote.com/l/ABWonhEnAJpDvqEwTmDVfIHVcINjPLYqRPAB/image.png)

## Install

```
$ composer require miya/custom-field-map
```

## How to use

```
<?php

require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

$map = new \Miya\WP\Custom_Field\Map( 'latlng', 'Latitude and Longitude' );
$map->add( 'post' ); // Set post type to display meta box.
```
