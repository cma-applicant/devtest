Instructions for running locally:

1) The front end is a few php pages which connect to a mysql database running locally. Create a local database named CMADemo and execute the commands in CreateTables.sql to create the db structure

2) The database credentials are used in both 'process-upload.php' and 'results.php'. In both files, replace the user/pass credentials of the $con variable with your own

3) To complete Part 1 of the application: With 'Part1.py' and 'cma-artworks.db' in same directory, run the following command to output the results to file:
	>python3 .\Part1.py > results.json

4) To complete part 2 of the application: copy and paste the content of results.json to the textbox in index.php and click 'Submit'. This will insert all the data into the local database and redirect the user to a webpage for viewing the results

To see the front end running in production, visit www.tims.pizza/cma

Note: at present, there is very little validation on the form submission. It hasn't fully been tested to prevent sql injection, so please be gentle :)
