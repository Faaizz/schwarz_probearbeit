- [Problem Description](#problem-description)
    - [Introduction](#introduction)
    - [Task 1](#task-1)
    - [Task 2](#task-2)
    - [Task 3](#task-3)
    - [Task 4](#task-4)
- [Solution](#solution)
  - [Starting The Application](#starting-the-application)
    - [Task 1: Mini-CMS](#task-1-mini-cms)
      - [Navigation](#navigation)
    - [Task 2: REST API](#task-2-rest-api)
    - [Task 3: User Overview](#task-3-user-overview)
    - [Task 4: Authentication](#task-4-authentication)
  - [Testing The Application](#testing-the-application)

# Problem Description
Work on the tasks the same way you work on tasks in your everyday work.
The written code should be protected by unit tests.
There is no need to pay attention to the frontend design, but it can be supplemented with a bootstrap theme.

It would be advantageous if the individual work steps were mapped using commits.
Create a README file that describes how to start the application.
Try to work on as much as possible in the time given to you.
You are welcome to provide us with the finished, executable project via any exchange platform, such as Bitbucket, Gitlab or Github.

### Introduction
A mini-CMS is to be set up in which portals (DE, EN,...) and associated pages (e.g. imprint, ...) can be maintained. Maintaining this content should be as easy and intuitive as possible for the CMS user. Validation must also be taken into account here (e.g. a portal has an ISO Alpha-2 country code).

### Task 1
Set up a Symfony application version 5 (or higher) within a Docker environment.
There should be 2 portals in the application:
• DE
• EN
Each portal has an legal page (e.g. localhost/de/impressum or localhost/en/imprint). Under this link, only the content for the appropriate language is output. A maintenance option should be created in which the legal page for the respective portal can be created or changed.

### Task 2
Create a REST API that can be used to retrieve the data from Task 1 as JSON.
Bonus: API documentation via OpenAPI YAML

### Task 3
A user overview is now to be built into the existing Symfony application. This should be accessible via localhost/users.
When called, the users are retrieved via a service from the following API: https://jsonplaceholder.typicode.com/users.
The individual users are then converted into value objects and displayed in tabular form.

### Task 4
Implement a login for the CMS. The login can be implemented via a database or oAUTH. There should be 2 roles:
• ROLE_USER
• ROLE_ADMIN
New pages and portals within the CMS may only be created and edited by the ROLE_ADMIN role. The ROLE_USER role only has read permission.


# Solution

## Starting The Application
To start a local development server:
```sh
./startDevServer
```

### Task 1: Mini-CMS
The base path for the Mini-CMS is [http://localhost:8000/portal/pages](http://localhost:8000/portal/pages). This gives the management page where portals and pages can be created, edited, and deleted.

#### Navigation
All available pages can be viewed on the path [http://localhost:8000/](http://localhost:8000/).

### Task 2: REST API
API Docs available at: [http://localhost:8000/api/doc](http://localhost:8000/api/doc).
The REST API is available at [http://localhost:8000/api/v1/portal/pages](http://localhost:8000/api/v1/portal/pages).

The CRUD endpoints are:
- Create: POST [http://localhost:8000/api/v1/portal/pages](http://localhost:8000/api/v1/portal/pages)
- Read: GET [http://localhost:8000/api/v1/portal/pages](http://localhost:8000/api/v1/portal/pages)
- Update: PUT/PATCH [http://localhost:8000/api/v1/portal/pages/{id}](http://localhost:8000/api/v1/portal/pages/{id})
- Delete: DELETE [http://localhost:8000/api/v1/portal/pages/{id}](http://localhost:8000/api/v1/portal/pages/{id})


### Task 3: User Overview
A table with retrieved users can be found at the path [http://localhost:8000/users](http://localhost:8000/users).

### Task 4: Authentication
Two users have been created for login:
1. User: `admin` with password: `password` and roles: `[ROLE_USER, ROLE_ADMIN]`
2. User: `user` with password: `password` and roles: `[ROLE_USER]`.

Additional users can be created via the [http://localhost:8000/register](http://localhost:8000/register) route. And an existing user can login via [http://localhost:8000/register](http://localhost:8000/login)


## Testing The Application
You can run all tests with: `./execTests`.
