SCOPE
This project was quite large in terms of scope. Rather than complete every part quickly with poor code and errors, I’d rather showcase some of my skills and take my time producing quality. Therefore, I did not complete every part of the assignment however, the parts I completed are done to a higher standard than likely expected.

LOCAL ENVIRONMENT
I first started out by trying to setup my own linux server, but I realized it would be time consuming to install everything. So I expedited the process and rather than use some software like Virtual Box, I opted to simply install XAMPP. I have setup linux servers in the past and used services like Amazon AWS and other hosting platforms. However, I’ve always found XAMPP to be the simplest setup for producing quick local environment.

SQL TABLES — see schemas.sql
I wrote some simple SQL statements to create the tables for the IP and Location data. I added foreign keys as discussed below based on my brief research.

SQL INSERTION CREATION — see tools/createIPSQL.php
I then wrote a PHP tool to create SQL from the CSVs. I wanted to really emphasize some of my skills here. So I wrote my SQL creation tool with OOP concepts in mind — I made a class with testing functions and a few properties storing the IP ranges (two arrays one for the low end, and one for the high end). I wasn’t exactly sure how the IP addresses where intended to be converted - I tried searching the function suggested in your email ’net_ntoa’. However, I wasn’t really too sure how all the IP information worked. My research indicates we were given a CIDR IP. It appears that with some bit manipulation this IP can be converted to a range. I copied a piece of code which I credited to StackOverflow, which does some masking to convert the IP. I’m still not entirely sure if I did this section properly. 

I created a binary search to find the appropriate range this IP belongs to. Essentially the ranges are stored in two arrays, a minimum and a maximum and then we try to locate the associating range to find the id. I wrote up some quick testing data, to validate my binary search and the results where positive. I then made the code a bit more generalized, where you can enter parameters into your terminal to indicate whether you would like to produce testing data or production data using the tool. The command must be run in the tools directory and could look like the following: 
— php createIPSQL.php test
— php createIPSQL.php prod

The tool also notes some errors — indicating if some of the data may be wrong or something is broken (the errors are echoed out).

Importing the Data
I used PHPMyAdmin for simplicity. However, there are more direct ways to import the data — for example you could use the command line. Because the data was large, I couldn’t copy-and-paste my insert statements, so rather I chose to import the whole file. I noted that some of the data is pretty bad. For example, there are quotation marks that will break the queries. I would try to refine my importation tool in the longrun to support this case and other’s that I find. However, for now I just imported the data that wouldn’t break my queries.
