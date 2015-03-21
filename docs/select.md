### select(A, [B])
     A is the full SQL, a table name, or a shortcut value.
     B an optional value that will limit the number of results returned.

### Returns
**FALSE** if it did not find any rows.  An object if rows were found.


----


### Examples
```php
$products = fw::select("products");
```
Select everything in the products table

```php
$products = fw::select("products", 50);
```
Select everything in the products table, limit to 50 results.

```php
$products = fw::select("products:23");
```
Select the row with the matching primary key value of 23 from the products table. (Sanitized Input) 

```php
$products = fw::select("products:cat_id:desc", 10);
```
Select everything in the products table, order by the cat_id, limit to 10 results.

```php
$products = fw::select("SELECT product_id, name AS 'name', description, cat_id FROM products");
```
Select allows for full SQL statments that would otherwise run the same as with mySQLi.

#### Examples with ->prep()
prep is short for prepare, as in Prepared Statement. It works the same with all other Framework methods (select, insert, update, delete) but will not work by itself.  You must use prep with a method chain of one of the other methods.  prep insures that you inputs are sanatized and free of malicious code. 

```php
$products = fw::prep("SELECT * FROM products WHERE cat_id = ?", "int:1")->select();
```
Select all rows from the products table that have a catID of 1.

```php
$products = fw::prep("SELECT * FROM products WHERE cat_id = ?", "int:{$_GET['category']}")->select();
```
Same example as above but with user supplied data.  Just to show you a real world example. 

```php
$products = fw::prep("SELECT * FROM products WHERE cat_id = ?", "int:{$_GET['category']}")->select(10);
```
Same example as above but with only the first 10 results returned. 

```php
$products = fw::prep("SELECT * FROM products WHERE cat_id = ? AND product_sub = ?", "int:{$_GET['category']}", "text:{$_GET['sub']}")->select();
```
You can add as many inputs or "?" into your SQL statement and keep adding arguments that will sanatize them.  You may have noticed, but one is an "int" and the other "text".  int and text are the only two possiblities.  While you could just specify everything as a "text" and it would work, for maxium preformance and security, numeric values should be treated differently with "int".

### Full Working Examples
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
This is a very basic example that would just repeat all the names of every product in that table. 

```php
$products = fw::prep('SELECT * FROM products WHERE cat_id = ?', "int:{$_GET['category']}")->select(50);

if($products)
{
    echo $products->totalRows . " total products found."
    foreach ($products as $product) {
       echo $product->name . "<br />";
    }
    $products->paging(NULL, TRUE, TRUE, 6);
} else {
    echo "No Products Found";
}
```
This example highlights the usage of recordCount and the paging method which, as long as your results are limited (our results are limited to 50) will automatically create the href links for paging through the results (i.e. < first, < previous 1 2 3 4 5 6 next > last >)

### paging() method
```php
paging($page = NULL, $displayFirstLast = FALSE, $displayPageNums = FALSE, $maxPagesSet = NULL)
```
Above are the defaults for that method.  Most are self explanatory.  The $maxPagesSet helps you to limit the numbers that will appear between the < previous and next > links.  This helps control the max number of numbers that should appear.  So that you don't get:
```php
< previous 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 next >
```
If you set that $maxPagesSet to say: 6 and the user had paged though to page 9 then it would look like:
```php
< previous 7 8 9 10 11 12 next >
```

### toArray() method
```php
$products = fw::select("products");
$arrayOfProducts = $products->toArray();
```

In the example above $arrayOfProducts would be set as a multidimensional array with a structure like the following:
```php
[row primary key value 1] =>
    array(
     	[column name 1] => column 1 data
	[column name 2] => column 2 data
    );
[row primary key value 2] =>
    array(
     	[column name 1] => column 1 data
	[column name 2] => column 2 data
    );
```

### Other useful static variables
```php
$products->recordCount //Outputs something like "1 to 2 of 13"
$products->id //id is always there and is always your table's primary key value, even if the name of that column is "product_id"
$products->totalRows //Total number of rows that were returned with the result set.

//Build your own paging system, links to make it work are easy to get. 
$products->nextPageHREF;
$products->prevPageHREF;
$products->firstPageHREF;
$products->lastPageHREF;
$products->pageHREF;
$products->curPage;
$products->firstPage;
$products->lastPage;

//Want to get really crazy?  Print_r the results of the result below:
$products->tableStructureAndInfo;
```

### Common pitfalls
```php
$products = fw::select('products');
echo $products->totalRows . " total products found."
    foreach ($products as $product) {
       echo $product->name . "<br />";
    }
    $products->paging(NULL, TRUE, TRUE, 6);
```
If the example above, or any of the examples shown in this document, were to not find any rows in the database then the value of $products is set to **FALSE** and thus will not be an object.  That means that you cannot just assume that the output will be an object because if there are no rows then the output of $products->totalRows would produce an error instead of a 0. 