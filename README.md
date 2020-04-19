### MidWork v2.0 Beta

**Requirements**: PHP 7.2 or higher, MySQL PDO

**Installation**

1. Use composer to install: `composer require midmyk/midwork2:dev-master`
or download zip archive and unpack it into private directory (not accessible from the web)
2. Move the contents of "public" folder to your public/web directory, for e.g. public_html and change path to your app/Config.php in public/index.php
3. Configure HOST (website url) in app/Config.php
4. Go to your website

**Update**

To get latest updates, just run `composer update`

### HOW-TOs

**How to specify custom port for my database connection?**

Uncomment or add the following to your app/Config.php
$config['db_port'] = 1234;

Optionally add the following to .env to have different port for local development environment
db_port = 1234

**How to select everything from a table?**

$users = Model::select_all('users');

You will get an array with rows.

**How to select specific row from a table knowing id?** 
Example with users table:

$user = Model::select('users', 1);
echo $user['name'];

**How to select specific row from a table knowing other column and value?**

$user = Model::select('users', ['name' => $name]);

**How to insert data into a table?**

Model::insert('table', ['column_one' => 'value1']);

**Delete by id**

Model::delete('users', 1);

**Delete by specific column**

Model::delete('users', 'email' => 'test@test.com');

**I need more advanced queries with like and joins etc**

Just use native PDO like this:

Db::conn()->prepare();

Db::conn()->execute();

Db::conn()->query();

etc.

Example:

$query = Db::conn()->query("SELECT * FROM USERS");

$users = $query->fetchAll()

print_r($users);