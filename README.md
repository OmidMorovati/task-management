
# Task Management
#### Simple task management application


## Requirements

#### PHP 8.1 | MySQL 8
## Installation

After clone the repository follow these steps:

Change the.env file according to your MySQL service parameters by running the following command:
```bash
cp .env.example .env
```   
Install packages :

```bash
composer install
```
Run migrations :

```bash
php artisan migrate
```
## Authentication APIs Reference

#### Register
```http
POST /api/register
```

| Body | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | **Required** |
| `password`      | `string` | **Required** , **At least 8 character**|
| `email`      | `string` | **Required** |

#### Login

```http
POST /api/login
```
| Body | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**|
| `password` | `string` | **Required**|

#### Me

Show current user informarion
```http
GET /api/me
```

| Header | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Authorization` | `bearer` | **Required**|


#### Logout

```http
POST /api/logout
```

| Header | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Authorization` | `bearer` | **Required**|

## User Api refrence 

#### view all users

view all users' names and emails
```http
GET /api/users
```

## Task CURD APIs Reference
All of the below APIs need Authorization header with bearer token
#### store a new task
```http
POST /api/task
```

| Body | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `title`      | `string` | **Required** |
| `description`      | `string` | **Nullable**|
| `deadline`      | `date` | **Required**  , **After today**|

#### update a task

```http
PUT /api/task/{task}
```
| Body | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `title` | `string` | **Required**  , **Sometimes**|
| `deadline` | `string` | **Nullable**|

#### delete a task

```http
DELETE /api/task/{task}
```


#### show a task

```http
GET /api/tasks/{task}
```


#### view all task

```http
GET /api/tasks
```
## Task Assignment APIs Reference

#### assign a task
```http
POST /api/task-assignments/assign
```

| Body | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `task_id`      | `string` | **Required** |
| `assignee_email`      | `string` | **Required**|


#### view all own assignments
```http
GET /api/task-assignments/own-assignments
```

#### approve a task
```http
POST /api/task-assignments/assign
```

| Body | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `task_id`      | `string` | **Required** |

## Update Delayed Task
To update delayed task statuses, follow these steps:

Go to your terminal, ssh into your server, cd into your project and run this command.

```bash
crontab -e
```
This will open the server Crontab file, paste the code below into the file, save and then exit.

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Do not forget to replace /path-to-your-project with the full path to the Artisan command of your Laravel Application.
## Running Tests


```bash
php artisan test
```

  

## Author

- [@OmidMorovati](https://github.com/OmidMorovati)

  
