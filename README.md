# 

# Symfony-Todo-App

A simple todo app using Symfony 4, the Twig template engine and Bootstrap.

## Getting Started

The first thing you need to do is git clone the repo. 

```
git clone https://github.com/famartin/Symfony-Todo-App.git
```

### Prerequisites

For this application to work, you will need MySQL and Apache2. You can also use Xampp, Wamp, or Mamp.

```
sudo apt-get update
sudo apt-get install mysql-server
sudo mysql_secure_installation
sudo mysql_install_db
```

```
sudo apt-get update
sudo apt-get install apache2
```

### Running

Once you have everything installed, go into the cloned repo and configure the .env.dist file

Replace "db_user" with your MySql username and "db_password" with your respective password

Don't forget to replace "db_name" with the name you want as well.

After that, run the command

```
./bin/console server:run
```

## Built With

* [Symfony](https://symfony.com/) - The web framework used
* [Twig](https://twig.symfony.com/) - The template engine used
* [Bootswatch](https://bootswatch.com/) - Used for themes

## ScreenShots

![ScreenShot](https://i.imgur.com/ykVBIiH.png)
![ScreenShot](https://i.imgur.com/HxuZ76t.png)
![ScreenShot](https://i.imgur.com/bw5uLA0.png)
![ScreenShot](https://i.imgur.com/pDSZYxd.png)
![ScreenShot](https://i.imgur.com/mu48wIo.png)
