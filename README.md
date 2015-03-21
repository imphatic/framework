# framework
Framework is a SQL DBMS that makes mySQL communication faster, lighter, and easier.

### Install

Download into your project and include framework.php 



### Sample Usage
```php
$products = fw::select('products');

if($products)
{
    foreach ($products as $product) {
       echo $product->name . "<br />";
    }
} else {
    echo "No Products Found";
}
```

### Documentation
See the docs folder for documentation on the four primary methods (select, insert, update and delete)

