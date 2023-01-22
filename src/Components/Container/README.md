<h1 align="center">Welcome to container-pattern-php 👋</h1>
<p>
  <img alt="Version" src="https://img.shields.io/badge/version-1.0.0-blue.svg?cacheSeconds=2592000" />
  <a href="#" target="_blank">
    <img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-yellow.svg" />
  </a>
</p>

> ⚠️ Huge refacto in progresse the doc is no longer up to date so usage section is not working anymore

### 🏠 [Homepage](https://github.com/niko-38500/container-pattern-php.git)

## Install

```sh
composer require life-style-coding/container-pattern
```

## Usage

step 1: To use it you have to provide the use statement for the package at your index.php 
```sh
use LifeStyleCoding\Container\Container;
```

step 2: Instanciate the container class

step 3: run the resolve method of the container into a instance variable and pass the class name as argument

step 4: run the execute method of the container and pass the instance variable and the class methods you wish to call as arguments

exemple : 
```sh
$class = "\\App\\Controller\\HomeController";
$method = "index";
$container = new Container();
$instance = $container->resolve($class);
$container->execute($instance, $method);
```

## Next update

<p>Adding a list of objects already instantiated, so as not to have to reinstate them</p>

<p>Handle routing with block comments</p>

## Author

👤 **Nicolas Montmayeur**

* Github: [@niko-38500](https://github.com/niko-38500)
* LinkedIn: [@nicolas-montmayeur-9b7b441ab](https://linkedin.com/in/nicolas-montmayeur-9b7b441ab)

## Show your support

Give a ⭐️ if this project helped you!

***
_This README was generated with ❤️ by [readme-md-generator](https://github.com/kefranabg/readme-md-generator)_