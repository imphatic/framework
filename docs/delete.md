### delete(A, [B])
	A is the full SQL, the table name that you are deleting data from, or a shortcut value.
	B an optional value that will redirect the page to the specified value after deleting data.

### Returns
Returns an object, but the data is really only useful for testing.  Use print_r to see what all is set.


----


### Examples
```php
fw::delete("products");
```
You must pass the primary key in a POST variable for this to work. So if we want to delete row 45 and our primary key column name was products_id then the POST products_id value would be set to 45.

```php
fw::delete("products", "view-products.php");
```
Same as above but redirects the user to view-products.php after deleting that row.

```php
fw::delete("products:" . $_GET['prod_id']);
```
Same as above (yes our primary key for the products table is still products_id) but we can use this shortcut to delete what ever row has the primary key value of GET prod_id.

```php
fw::delete("products:name:" . $_GET['product_name'])
```
In this example we are deleting the row with the column name set as GET product_name.

### Examples with ->prep()
prep is short for prepare, as in Prepared Statement. It works the same with all other Framework methods (select, insert, update, delete) but will not work by itself.  You must use prep with a method chain of one of the other methods.  prep insures that you inputs are sanatized and free of malicious code. 

```php
fw::prep("DELETE FROM products WHERE product_id = ?", "int:" . $_GET['prod_id'])->delete("view-products.php")
```
Example using prep with the redirect to view-products.php.

### Full Working Examples
```php
if(isset($_POST['delete'])) 
{
  fw::delete('products:' . $_POST['prod_id'], 'view-products.php');
}
```