# CSY2028 Web Programming - Assignment 02 (Jo's Jobs)

Jo's Jobs is a small recruitment agency website used to advertise jobs to applicants. The site is built from the ground up using PHP, MySQL, HTML, CSS and JavaScript.

## Disclaimer

This project is not suited for production. This is entirely for assignment/demonstration purposes.

## How it Works

Visitors can view the jobs and apply for jobs they're interested in and make general enquiries through the contact page. The site has an administrator/client password-protected area which is accessed at `https://jos-jobs.v.je/id/login`. The default login credentials are:

- Email: jo@josjobs.com
- Password: letmein

Once here the user, depending on permission levels, can view different areas of the admin area.

## Local Environment Setup

To run this website I'm using a pre-built/configured virtual machine available through Vagrant. The box and details about it can be found at <https://r.je/vje-minimal-virtual-server>.

1. Install Virtual Box, Vagrant, MySQL Workbench, Composer and Git.
2. In your terminal application, navigate to an empty directory and run vagrant init `csy2028/current`
3. Run `vagrant up`
4. Open MySQL Workbench and create a new connection using the DB information below
5. Back in the terminal application, navigate inside `websites` and run `git clone git@github.com:jenewland1999/csy2028_as2 jos-jobs/`
6. Now run `cd jos-jobs` followed by `composer install`
7. Import database.sql into MySQLWorkbench (Ensure schema is called josjobs)
8. Open your web browser of choice and type `https://jos-jobs.v.je/`
9. Ta-da! Jo's Jobs is running.

## Database Connection Info

- Host: v.je
- Port: 3306
- Username: student
- Password: student

## Unit Testing

This project uses PHPUnit to perform automated unit testing. The test suite focuses on testing form submissions for creating, updating and deleting various resources (applications, categories, enquiries, jobs, locations and users).

To run the unit tests open your favourite terminal program in the project directory and run the following command:

```bash
./vendor/bin/phpunit --coverage-html=./report
```

To view the coverage report open `./report/index.html` in your favourite web browser.

---

Copyright &copy; 2020 Jordan Newland
