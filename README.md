# A Demo of RESTful API using PHP
## Introduction:
- This is the simple PHP Restful API which is implemented without using frameworks.
- In this demo project, I built login/logout function and implemented an endpoint (/users) to consume request from client so that we can select and update user's data (such as name, address, phone ...)
- I try to implement this demo follow single-page's for login/logout so that we nearly unchange the URI when login/logout.
- I also try to implement some function to secure the login process and API calling such as: prevent cookies thief, using pair of email address and access key to enable API call.

There are 2 main functions in this demo:
- User Login / Logout to view user's information and API Key
(API Key is an base64 encoded string)
- 2 endpoints to call API (/users and /users/:id)

## Installation demo:
0. Ensure mode_rewrite and PDO are support by your web server.
1. Import db.sql (include in source) to your.
2. Change connection detail in helper.php file.
3. Copy source files to your web root directory.

## Run demo:
1. View database record to get username and password to login.
2. When logged in you will see API Key in User's information.
3. Using "email:key" as user parameter in your request header to call API.

## API Access:
- http://example.com/api/users to get users list. Only GET is supported.
- http://example.com/api/users/:id to get and update user. GET and PUT are supported

## Authentication:
Each request must be authenticated with user’s e-mail and API key.

Request Example:
- GET: curl --user admin@example.com:APIkey -X GET 'http://example.com/api/users/1'
- PUT: curl --user admin@example.com:APIkey --header 'Content-Type: application/json' -d '{"name":"Neymar","address":"PSG FC, France","telephone":"0109090909"}' -X PUT 'http://example.com/api/users/1’
