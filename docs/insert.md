### insert(A, [B])
      A is the full SQL, the table name that you are inserting data, or a shortcut value.
      B an optional value that will redirect the page to the specified value after inserting data.

### Returns
object with $object->id set as the primary key value of the inserted row.

----

### Examples
```php
$new = fw::insert("products");
```
In this example framework will look for POST names that match up with column names in the products table.  So if our columns are "name,description,cat_id", then framework will look for matching $_POST['name'], $_POST['description'] and $_POST['cat_id'] and insert those values into a new row.  All inputs are auto sanitized. 

```php
$new = fw::insert("products", 'view-products.php');
```
Same as above but after it inserts, it will redirect the user to 'view-products.php'

```php
$new = fw::insert("products:name,description")
```
You can limit the columns that framework looks for by doing this shortcut above.  Even if $_POST['cat_id'] were passed, framework would only insert the row with data for name and description. All inputs are auto sanitized.

```php
 $sql = "INSERT INTO products (cat_id, type_id, name, `description`) VALUES ($category, $typeID, '{$_POST['name']}', '{$_POST['description']}')";
 $new = fw::insert($sql);
```
You can also use fully qualified SQL statements.  NOTE this example does NOT have sanitized inputs so this one would be vulnerable to a SQL Injection Attack.  

### Examples with ->prep()
prep is short for prepare, as in Prepared Statement. It works the same with all other Framework methods (select, insert, update, delete) but will not work by itself.  You must use prep with a method chain of one of the other methods.  prep insures that you inputs are sanatized and free of malicious code. 

```php
$new = fw::prep('INSERT INTO products (cat_id, type_id, name, `description`) VALUES (?,?,?,?)', 
'int:{$_GET['category']}', 
'int:2', 'text:{$_POST['product_name']}', 
'text:{$_POST['desc']}')->insert();
```
Same as the last example, but utilizes the prep method to sanatize inputs.

```php
$inputArray = array('int:{$_GET['category']}', 
                    'int:2', 
                    'text:{$_POST['product_name']}', 
                    'text:{$_POST['desc']}');

$new = fw::prep('INSERT INTO products (cat_id, type_id, name, `description`) VALUES (?,?,?,?)', $inputArray)->insert();
```
Same as above, but shows that you can pass an array for all the inputs, instead of adding arguments to the prep method.  Note that you cannot do both at the same time.  Either an additional argument for each input, or an array with all the inputs. 

```php
$new = fw::prep('products:cat_id,type_id,name,description', 
       'int:{$_GET['category']}', 
       'int:2', 'text:{$_POST['product_name']}', 
       'text:{$_POST['desc']}')->insert();
```
This is a shorter version of the examples above using a shortcut.  You can pass an array for the inputs here as well.

### Full Working Examples
```php
if(isset($_POST['name'])) 
{
    $inputArray = array('int:{$_GET['category']}', 
                    'int:2', 
                    'text:{$_POST['product_name']}', 
                    'text:{$_POST['desc']}');
    $new = fw::prep('products:cat_id,type_id,name,description', $inputArray)->insert();
    echo "Row created with the ID of " . $new->id;
}

```
Note that, just like select, the id value is always set, even if your primary key is actually 'products_id'.  

```php
if(isset($_POST['name'])) 
{
    $inputArray = array('int:{$_GET['category']}', 
                    'int:2', 
                    'text:{$_POST['product_name']}', 
                    'text:{$_POST['desc']}');
    $new = fw::prep('products:cat_id,type_id,name,description', $inputArray)->insert('view-products.php');
}

```
Same as above but showing that you can also have the insert method redirect to another page after it inserts.