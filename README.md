# League Of Stats

League Of Stats is a single-page application which allows League of Legends users to easily look up information and statistics about their accounts by utilizing Riot Games's API.

Utilizes League of Legends API, HTML, CSS, JavaScript, Vue.js, Ajax, PHP, Laravel, Eloquent ORM and MySQL.

![1](https://user-images.githubusercontent.com/16446652/93604797-8cdb8f80-f9ce-11ea-9f65-b76a29707ad6.png)

The application consists of a Vue.js front-end and PHP (Laravel) back-end which are separated from each other. Separating the front-end from the back-end has many advantages such as:

* Readability
* Flexibility
* Scalability
* Easy maintenance
* Modularity
* Easier deployment

**Front-end responsibilities:**

* Make requests to the back-end for information about League Of Legends users and matches
* Display all the information in an understandable manner
* Handle routing

**Back-end responsibilities:**

* Make requests to Riot Games's API and then return the responses to the front-end
* Store the API responses inside the database
* Obfuscate Riot Games's API key

![2](https://user-images.githubusercontent.com/16446652/93605056-ef349000-f9ce-11ea-90c0-e2cef9f08551.png)
