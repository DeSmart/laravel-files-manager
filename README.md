# desmart/files

This package handles files upload in friendly DDD manner.

# Installation

In console run:

```bash
composer require desmart/files:2.0.*
```

## Laravel

Add `DeSmart\Files\ServiceProvider\ServiceProvider` to providers list.

In console run:

```bash
php artisan vendor:publish
php artisan migrate
```

## Lumen

Add this line to `bootstrap/app.php` file:

```php
$app->register('DeSmart\Files\ServiceProvider\LumenServiceProvider');
```

In console run:

```bash
cp vendor/desmart/files/database/migrations/* database/migrations/
cp vendor/desmart/files/config/desmart_files.php config/desmart_files.php
php artisan migrate
```

# Configuration

## Storage

This package uses Laravels storage mechanism. By default package uses `upload` disk which needs to be defined in `config/filesystems.php`.

```php
<?php
// config/filesystems.php
return [
    'disks' => [
        'upload' => [
            'driver' => 'local',
            'root'   => public_path('upload'),        
        ],
    ],
];
```

## Mappers

Before saving file it can be mapped by a mapper. Mapper receives generated `FileEntity` and can change its properties. Based on entity data file will be saved in filesystem, and database.

Mappers must implement `DeSmart\Files\Mapper\MapperInterface`.

## Custom File Entity class

By default package uses `DeSmart\Files\Entity\FileEntity`. This can be changed in `desmart_files.file_entity_class` config entry.

# Examples

## Storing file from upload

```php
<?php
$file = \Request::file('file');
$source = new \DeSmart\Files\FileSource\UploadedFileSource($file);

// I'm assuming that Manager instance will be injected by Laravel Container
$manager = app('DeSmart\Files\Manager');

// Here we have the FileEntity instance
// File is saved on the filesystem and in the database
$entity = $manager->store($source);

// from here you have save the relation with other entity
// this is just example!

$user->addFile($entity);
$user->save();
```

## Removing file from storage

This method will work only when given `FileEntity` has no entries in `file_records` table.

```php
<?php
$file = new FileEntity; // $file should be obtained in different way (e.g through a relation)

$manager = app('DeSmart\Files\Manager');
$manager->remove($file);
```
