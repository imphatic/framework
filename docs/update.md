### update(A, [B])
	A is the full SQL, the table name that you are updating data, or a shortcut value.
	B an optional value that will redirect the page to the specified value after updating data.

### Returns
Returns an object, but the data is really only useful for testing.  Use print_r to see what all is set.


----


### Examples
```php
fw::update("products");
```
In this example framework will look for POST names that match up with column names in the products table.  So if our columns are "name,description,cat_id", then framework will look for matching $_POST['name'], $_POST['description'] and $_POST['cat_id'] and update those values.  All inputs are auto sanitized. 

**IMPORTANT:** For this to work you must pass the primary key with your POST data.  So if our primary key is products_id, then along with all our data that we want to update, we will need to pass a POST variable with the ID of the row that we want to update.  It will give a very descriptive error if you don't do this.  

```php
fw::update("products", 'view-products.php');
```
Same as above but after it updates, it will redirect the user to 'view-products.php'

```php
fw::update("products:name,description")
```
You can limit the columns that framework looks for by doing this shortcut above.  Even if $_POST['cat_id'] were passed, framework would only update the row with data for name and description. All inputs are auto sanitized.

```php
 $sql = "UPDATE products SET name = '{$_POST['name']}', description = {$_POST['description']} WHERE productID = {$_POST['product_id']}";
fw::update($sql);
```
You can also use fully qualified SQL statements.  NOTE this example does NOT have sanitized inputs so this one would be vulnerable to a SQL Injection Attack.  

### Examples with ->prep()
prep is short for prepare, as in Prepared Statement. It works the same with all other Framework methods (select, insert, update, delete) but will not work by itself.  You must use prep with a method chain of one of the other methods.  prep insures that you inputs are sanatized and free of malicious code. 

```php
fw::prep('UPDATE products SET `name` = ?, `description` = ? WHERE productID = ?"',
            'text:{$_POST['name']}', 'text:{$_POST['desc']}', 'int:{$_POST['product_id']}')->update();
```
Same as the last example, but utilizes the prep method to sanatize inputs.

```php
$inputArray = array('text:{$_POST['name']}', 
                    'text:{$_POST['desc']}', 
                    'int:{$_POST['product_id']}');

fw::prep('UPDATE products SET `name` = ?, `description` = ? WHERE productID = ?"', $inputArray)->update();
```
Same as above, but shows that you can pass an array for all the inputs, instead of adding arguments to the prep method.  Note that you cannot do both at the same time.  Either an additional argument for each input, or an array with all the inputs. 

```php
fw::prep('products:name,description,product_id', 
       'text:{$_POST['name']}', 'text:{$_POST['desc']}', 'int:{$_POST['product_id']}')->update();
```
This is a shorter version of the examples above using a shortcut.  You can pass an array for the inputs here as well.  Note that the last input must be the primary key of the row that you are updating.  The order of the other inputs does not matter.

### Full Working Examples

```php
if(isset($_POST['name'])) 
{
    $inputArray = array('text:{$_POST['name']}', 
                        'text:{$_POST['desc']}', 
                        'int:{$_POST['product_id']}');
    fw::prep('products:name,description,product_id', $inputArray)->update('view-products.php');
}

```
After updating the data in the products table the script will direct the user to view-products.php