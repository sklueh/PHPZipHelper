ZipHelper
=========
A simple class for creating zip files.

**Example:**
```php
$oZipHelper = new ZipHelper("my_archiv.zip");

$oZipHelper->addSource('*.txt')
           ->addSource('*.php')
           ->addSource('../')
           ->addSource('/test.txt')
           ->addSource(array('/home/sklueh/write.sh', 
                             '/home/sklueh/config.php'))
           ->addSource('/home/sklueh/my_directory')
           ->create();
```

For more information visit the following link:

http://sklueh.de/2013/08/php-zip-archiv-erstellen/
