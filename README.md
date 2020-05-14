SCOPE
This project was quite large in terms of scope. Rather than complete every part quickly with poor code and errors, I’d rather showcase some of my skills and take my time producing quality. Therefore, I did not complete every part of the assignment however, the parts I completed are done to a higher standard than likely expected.

LOCAL ENVIRONMENT
I first started out by trying to setup my own linux server, but I realized it would be time consuming to install everything. So I expedited the process and rather than use some software like Virtual Box, I opted to simply install XAMPP. I have setup linux servers in the past and used services like Amazon AWS and other hosting platforms. However, I’ve always found XAMPP to be the simplest setup for producing quick local environment.

The setup shows my understanding of several different methods to create a server. It also shows my ability to decide the best method for the task (in this case, we needed to very quickly setup a local dev).

Note: I excluded the server code and have only shared code I wrote (all the XAMPP config files, etc. aren’t in the zip file).

SQL TABLES — see schemas.sql
I wrote some simple SQL statements to create the tables for the IP and Location data. I added foreign keys as discussed below based on my brief research.

My schemas indicate that I have some understanding of databases and their relationships.

SQL INSERTION CREATION — see tools/createIPSQL.php
I then wrote a PHP tool to create SQL from the CSVs. I wanted to really emphasize some of my skills here. So I wrote my SQL creation tool with OOP concepts in mind — I made a class with testing functions and a few properties storing the IP ranges (two arrays one for the low end, and one for the high end). I wasn’t exactly sure how the IP addresses where intended to be converted - I tried searching the function suggested in your email ’net_ntoa’. However, I wasn’t really too sure how all the IP information worked. My research indicates we were given a CIDR IP. It appears that with some bit manipulation this IP can be converted to a range. I copied a piece of code which I credited to StackOverflow, which does some masking to convert the IP. I’m still not entirely sure if I did this section properly — especially because a lot of the foreign keys are the same. 

I created a binary search to find the appropriate range this IP belongs to. Essentially the ranges are stored in two arrays, a minimum and a maximum and then we try to locate the associating range to find the id. I wrote up some quick testing data, to validate my binary search and the results where positive. I then made the code a bit more generalized, where you can enter parameters into your terminal to indicate whether you would like to produce testing data or production data using the tool. The command must be run in the tools directory and could look like the following: 
— php createIPSQL.php test
— php createIPSQL.php prod

The tool also notes some errors — indicating if some of the data may be wrong or something is broken (the errors are echoed out). I also echo out a percentage completed, that way you can understand approximately how long the SQL will take to produce (based off the 2.7 mill rows) — it isn’t dynamic however this feature could easily be improved

This section indicates my ability to perform some basic research, to create OOP code, to write some testing code/cases, to optimize an algorithm (using binary search), to read in a file and to produce SQL/PHP code.

Importing the Data
I used PHPMyAdmin for simplicity. However, there are more direct ways to import the data — for example you could use the command line. Because the data was large, I couldn’t copy-and-paste my insert statements, so rather I chose to import the whole file. 

I noted that some of the data is pretty bad. For example, there are quotation marks that will break the queries. I went back and added a few cases into the sqlCreation tool I previously wrote which I would implement and test better in the future. In the longterm I would refine my import tool to handle other cases find.

Connecting to the Database — see /htdocs/mysqli_connect.php and /htdocs/testDB.php
I created a user which I called amin who can perform basic SQL operations (insert, update, etc.). I entered this user, and some additional information into a config. First we created a config, containing information to connect to our database. We created a test to indicate that we connected to our database.

Creating the Map
I setup index.php as or main file (to access go to localhost:8080/index.php). I created a MapBox account and got my API key. I created a few HTML files and included them in my index.php file. These files pulled up the map. 

Goals
Most of the things that I haven’t completed yet, I have done before. Figuring them out wouldn’t be that difficult, it would just require some extra time. Here’s a list indicating, I know where to take the project next.
1. We haven’t installed the PHP framework (CodeIgniter) — creating better file structure (MVC)
2. We haven’t put our data AND tools onto the XAMPP server (they are on a separate directory) — need to use some terminal commands to transfer them
3. We have created the actual application
    1. Adding a bunch onto the page — creating some actual content
    2.  Used the Map API (use cURL or AJAX to send out the data to MapBox and acquire the new points entered)
4. We could create our own local host server, rather than use XAMPP (more control) AND in the final product pay for a hosting platform to put prod on
5. We could add some more data verifications and automatic data cleanup
6. Install a JavaScript Framework
7. Add apache redirection to index.php from all broken links and create a domain
