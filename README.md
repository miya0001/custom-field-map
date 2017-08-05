# miya/custom-field-map

A custom field to save latitude and longitude for WordPress

## Install

```
$ composer require miya/custom-field-map
```

## How to use

```
<?php

require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

$id = 'map';               // The identifier of the meta box.
$title = 'Map';            // The title of the meta box.
$post_type = 'geometry';   // The post type to add meta box.

$map = new \Miya\WP\Custom_Field\Map( $id, $title );
$map->add( $post_type );
```

![](https://www.evernote.com/l/ABXexIRE6etKZZnLfuOgw3mB0vfvwQJpJPAB/image.png)
