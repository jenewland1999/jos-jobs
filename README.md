# CSY2028 Web Programming - Assignment 02 (Jo's Jobs)

Jo's Jobs is a small recruitment agency website used to advertise jobs to applicants. The site is built from the ground up using PHP, MySQL, HTML, CSS and JavaScript.

## Disclaimer

This project is not suited for production. This is entirely for assignment/demonstration purposes.

## How it Works

Applicants can view the jobs and apply for jobs they're interested in.

## Local Environment Setup

To run this website I'm using a pre-built/configured virtual machine available through Vagrant. The box and details about it can be found at <https://r.je/vje-minimal-virtual-server>.

1. Install Virtual Box, Vagrant, MySQL Workbench and Git.
2. In your terminal application, navigate to an empty directory and run vagrant init `csy2028/current`
3. Run `vagrant up`
4. Open MySQL Workbench and create a new connection using the DB information below
5. Create a DB Schema called `assignment` then save it and exit MySQL Workbench
6. Back in the terminal application, navigate inside `websites` and run `git clone git@github.com:jenewland1999/csy2028_as2 jos-jobs/`
7. Open your web browser of choice and type `https://jos-jobs.v.je/`
8. Ta-da! iBuy Auctions is running.

## Database Connection Info

- Host: v.je
- Port: 3306
- Username: student
- Password: student

---

Copyright &copy; 2020 Jordan Newland
