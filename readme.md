# PHP Challange Jobsity


Setting up environments could be really painful sometimes...
thats why I keep it simple just run 

`composer setup` 

and a Script will do the following:

* It will copy the .env.sample file into .env file
* It will generate all docker containers for the app
* It will install all dependencies
* It will run the migrations && seeds


# API Documentation
### HTTP Codes
* `200` API request successful
* `201` API created resource successfully
* `400` API request returned an error
* `401` Unauthorized (access token missing/invalid/expired)
* `404` API endpoint not found
### Authentication
Endpoint | Parameters                                                   | Description
--- |--------------------------------------------------------------| ---
`POST /users` | `email` *string* required<br>`password` *string* required    | creates a user
`POST /users/login` | `username` *string* required<br>`password` *string* required | generates user access token
### Endpoints
All RESTful API endpoints below require a `Authorization: Bearer xxxx` header set on the HTTP request, *xxxx* is replaced with token generated from the Authentication API above.
#### Stock
Endpoint     | Parameters                       | Description
--- |----------------------------------| ---
`GET /stock`   | `q` *query string* required<br>  | represents the market to query<br> i.e ?q=aapl.us
`GET /history` | *N/A*                            | retrieve the history of queries made to the API service by that user

`note: inside of assets you can find a postman collection with all endpoints already set`
