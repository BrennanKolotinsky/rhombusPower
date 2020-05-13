<?php

class createSql {

	private $minIp; # min and max ip are two arrays, we use to run a binary search to connect our sql statements (thanks leetcode for the ideas!)
	private $maxIp;

	function construct() {

		// initialize variables
		$this->minIp = array();
		$this->maxIp = array();
	}

	/*
	This function runs the sql creation processes
	*/
	function buildSQL() {
		# let's create both of the sql files here
		echo "Starting!";
		$this->createLocation();
		echo "\nFinished Location creation, now let's create the IPs!";

		$this->createIP();
		echo "\nFinished creating all SQL";
	}

	/*
	This function creates the SQL to insert into the location table
	Input: None
	Output: None
	*/
	function createLocation() {
		
		$row = 1;
		$input = fopen("LOCATION.csv", "r");
		$output = fopen("location.sql", "w");

		if ($input !== NULL) {
		  
			while (($data = fgetcsv($input, 1000, ",")) !== NULL && $row < 100) {

				$this->minIp[$row - 1] = $data[0];
				$this->maxIp[$row - 1] = $data[1];
		    
				$sql = "INSERT INTO Location (Id, IPFrom, IPTo, CountryISOCode, Country, State, City, Latitude, Longitude) \nVALUES ({$row}, {$data[0]}, {$data[1]}, '{$data[2]}', '{$data[3]}', '{$data[4]}', '{$data[5]}', {$data[6]}, {$data[7]});\n\n";

				$row++;
				fwrite($output, $sql);
			}

			// free up resources
		  	fclose($input);
		  	fclose($output);
		}
	}

	/*
	This function creates the SQL to insert into the IP table
	Input: None
	Output: None
	*/
	function createIP() {
		
		$row = 1;
		$input = fopen("IP_addresses.csv", "r");
		$output = fopen("ip.sql", "w");

		if ($input !== NULL) {

			$data = fgetcsv($input, 1000, ","); // skip the first row because of headers
		  
			while (($data = fgetcsv($input, 1000, ",")) !== NULL && $row < 100) {

				$longIp = ip2long($data[0]);
				$correspondingLocationId = $this->binarySearch($longIp);
		    
				$sql = "INSERT INTO IPAddress (Id, Network, GeonameId, ContinentCode, ContinentName, CountryISOCode, CountryName, isAnonymousProxy, isSatelliteProvider, LocationId) \nVALUES ({$row}, '{$data[0]}', {$data[1]}, '{$data[2]}', '{$data[3]}', '{$data[4]}', '{$data[5]}', {$data[6]}, {$data[7]}, {$correspondingLocationId});\n\n";

				$row++;
				fwrite($output, $sql);
			}

			// free up resources
		  	fclose($input);
		  	fclose($output);
		}
	}

	/*
	This function searches for the appropriate location that associates with this addresses
	We use binary search to reduce runtime from O(N) to O(logN) -- a significant improvement in large datasets
	Input:
		Long ip address (convert from String first)
	Output:
		Int location id
	*/
	function binarySearch($ip4) {

		$lo = 0;
		$hi = sizeof($this->minIp);

		while ($lo <= $high) {

			$mid = (int) ($lo + ($hi - $lo) / 2); // prevents overflow! (rather than (lo + hi) / 2)

			if ($this->minIp[$mid] < $ip4 && $this->maxIp[$mid] > $ip4)
				return $mid + 1;
			# we are less than the current point
			else if ($this->minIp[$mid] > $ip4)
				$lo = $mid + 1;
			else
				$hi = $mid - 1;
		}

		return $lo + 1; // +1 because the ID starts at 1 (not zero)
	}
	
	/*
	this is an additional function I used for testing purposes
	*/
	function test() {
	}
}

$obj = new createSql();
$obj->buildSql();