
# hotBerry

> “A fool's brain digests philosophy into madness, science into superstition and art into pedantry. Hence a university education. ” George Bernard Shaw

<br />
<p align="center"><img src=".github/home.gif?raw=true"/></p>

---

# :pushpin: Table of Contents

* [Description](#memo-description)
* [Features](#rocket-features)
* [Installation](#construction_worker-installation)
* [Getting Started](#runner-getting-started)
* [Found a bug? Missing a specific feature?](#bug-issues)
* [Contributing](#tada-contributing)
* [License](#closed_book-license)

# :memo: Description
This project proposed the development of a system that uses a Raspberry Pi computer connected to a house boiler/immersion system to allow remote control of the hot water supply. This would provide the users with the convenience of having hot water for showers beforehand at any required time. To accomplish this, a web application was developed and made available online using a web hosting provider. The web application allows the user to schedule the boiler system to be turned ON/OFF from anywhere, through any device with a browser and internet connection. The Raspberry Pi is set-up at the user's house with an internet connection. It periodically poll’s the online web application’s back-end checking for user’s schedules and turns the boiler system ON and OFF accordingly. The web application also allows the user to keep track of the boiler/immersion system usage. The application was tested using a lamp to simulate the boiler/immersion system, and a functioning version is ready to be demonstrated.
To the Database Design Phase was used ER Diagram and used MySQL Data Definition Language to create the database and relations, tables for the System.
To develop the Web Application was used PHP, Hypertext Preprocessor. For the design was used Bootstrap, a framework that contains CSS, HTML and JavaScript based templates. Some JavaScript was also used outside the Bootstrap framework to achieve some functionalities, but the core of the application is in PHP.

# :rocket: Features

* ⌛️  Explore and manage schedules.
* 🛠  Create an account to manage users and devices (immotion systems).
* 📨  As an administrator of the platform, you're able to add other users.
* 🛰  Turn on/off via any mobile dispositive or computer.
* 🛀🏻  Programming an schedule as you please.

# :construction_worker: Installation

**You need to install at least [PHP 7.2](https://www.php.net/downloads/) and [MySQL 5.7](https://www.mysql.com/downloads/), I would recommend [Xampp](https://www.apachefriends.org/download.html), then in order to clone the project via HTTPS, run this command:**

```git clone https://github.com/marcosvaldeni/hotBerry.git```

SSH URLs provide access to a Git repository via SSH, a secure protocol. If you have a SSH key registered in your Github account, clone the project using this command:

```git clone github.com/marcosvaldeni/hotBerry.git```

# :runner: Getting Started

**Importing database**

In order to prepare the database, run the content of the file [hotBerry.sql](https://github.com/marcosvaldeni/hotBerry/blob/master/hotBerry.sql) on the phpMyAdmin `console`.

**Setup the credetials**

On the file [connection.php](https://github.com/marcosvaldeni/hotBerry/blob/master/hb/util/connection.php) locate at `hotberry/hb/util/connection.php`, change the connection variables:

  ```php
  $HOST = 'localhost';
  $USER = 'root';
  $PASS = '';
  $DB = 'hotberry';
  ```

**Creating a new user**

This platform was designed in a way that require a token to create a new admin user. Which in the design theory would come with the physical device as a license. 
The fallowing code will serve as token:

```
  8AF11A
  QL7IBV
  3UMX9P
  QW158Z
```

**Admin user and password for test**

If you just want to test the platform, use the test login and password: 
login: `caroldanvers@email.com`, password: `cct123`.

# :bug: Issues

Feel free to **file a new issue** with a respective title and description on the the [HotBerry](https://github.com/marcosvaldeni/FinalProject/issues) repository. If you already found a solution to your problem, **i would love to review your pull request**! Have a look at our [contribution guidelines](https://github.com/marcosvaldeni/hotBerry/blob/master/CONTRIBUTING.md) to find out about the coding standards.

# :tada: Contributing

Check out the [contributing](https://github.com/marcosvaldeni/hotBerry/blob/master/CONTRIBUTING.md) page to see the best places to file issues, start discussions and begin contributing.

# :closed_book: License

Released in 2019.
This project is under the [MIT license](https://github.com/marcosvaldeni/hotBerry/blob/master/LICENSE).

Made with love by [Marcos Lucas](https://github.com/marcosvaldeni) 💚🚀