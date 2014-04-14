# *Syn*ergy

## Introduction

*Syn*ergy is a web application for exploring gene regulation in *Synechocystis*.
The following documentation is mainly meant for developers. Documentation on how
to use *Syn*ergy you can find over at http://synergy.plantgenie.org/documentation.

## Requirements

* Python 2.7+ with pip
* PHP5+
* Apache server with PHP5 support
* MySQL
* MEME suite

## Installation

### Basic setup

First of all, clone the repository (or just download it):

```bash
git clone https://bitbucket.org/maehler/synergy.git
```

Of course, you will have to point you web server to this directory in order to
serve it.

Inside the synergy directory, you must now create a python virtual environment
that the application can use:

```bash
pip install virtualenv # If not already done
cd synergy
virtualenv .venv # Create virtual environment. Name is important!
source .venv/bin/activate # Activate the virtual environment
pip install -r requirements.txt # Install required modules, will take some time
...
deactivate # Deactivate the virtual environment
```

### Database

Currently, to get a dump of the database, please contact me at
niklas.mahler@nmbu.no. After you have the dump, load it into a database that
you have created, e.g. `synergy_db`

```bash
mysql -u user -p synergy_db < synergy_dump.sql
```

Next, you have to set the database credentials for both Python and CodeIgniter.
For CodeIgniter, edit `application/config/database.php` and add the credentials
for the `$db['default']` variable.

For Python, create the file `src/python/config/local.py` where you add the
database credentials in a dictionary named `DATABASE`

```python
DATABASE = {
    'user': 'username',
    'host': 'localhost',
    'pass': 'password',
    'database': 'synergy_db'
}
```

### Other constants

There are some other constants that *Syn*ergy needs to function properly.

In PHP you have to set the paths to the bin-directory of your MEME installation
and a temporary directory for storing e.g. TOMTOM results. Add the following
to `application/config/constants.php`:

```php
define('MEME', '/absolute/path/to/meme/bin');
define('TMP', '/absolute/path/to/tmp/directory');
```

Preferably set the temporary directory to `data/tmp` in the *Syn*ergy
repository, i.e. `/path/to/synergy/data/tmp`.
