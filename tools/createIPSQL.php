<?php

ini_set("memory_limit",-1); # increase memory to store large arrays

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
	Input:
		Boolean testing -- indicates whether we are currently testing
	Output:
		None
	*/
	function buildSQL($test = false) {
		# let's create both of the sql files here
		echo "Starting!";
		$this->createLocation($test);
		echo "\nFinished Location creation, now let's create the IPs!";

		$this->createIP($test);
		echo "\nFinished creating all SQL\n";
	}

	/*
	This function creates the SQL to insert into the location table
	Input:
		Boolean testing -- indicates whether we are currently testing
	Output: None
	*/
	function createLocation($test = false) {
		
		$row = 1;
		$input = fopen("../productionData/LOCATION.csv", "r");
		$output = fopen("../productionData/location.sql", "w");

		if ($test === true) {
			$input = fopen("../testData/locationTest.csv", "r");
			$output = fopen("../testData/locationTest.sql", "w");
		}

		$percent = 0;

		if ($input !== false) {
		  
			while (($data = fgetcsv($input, 1000, ",")) !== false) {

				// one test runs, let's break after one hundred lines have been created
				if ($test && $row > 100) {
					break;
				}

				// every 67500 rows we have finished another 2.5% approximately
				if ($row % 67500 == 0) {
					$percent += 2.5;
					echo "\n{$percent} %";
				}

				$this->minIp[$row - 1] = $data[0];
				$this->maxIp[$row - 1] = $data[1];
		    
				$sql = "INSERT INTO Location (Id, IPFrom, IPTo, CountryISOCode, Country, State, City, Latitude, Longitude) \nVALUES ({$row}, {$data[0]}, {$data[1]}, '{$data[2]}', '{$data[3]}', '{$data[4]}', '{$data[5]}', {$data[6]}, {$data[7]});\n\n";

				$row++;
				fwrite($output, $sql);
			}

			if ($test === true) {
				sort($this->minIp);
				sort($this->maxIp);
			}

			// free up resources
		  	fclose($input);
		  	fclose($output);
		} else {
			echo $test === true ? "\nLocation test data not found" : "\nLocation production data not found" ;
		}
	}

	/*
	This function creates the SQL to insert into the IP table
	Input:
		Boolean testing -- indicates whether we are currently testing
	Output: None
	*/
	function createIP($test = false) {
		
		$row = 1;
		$input = fopen("../productionData/IP_addresses.csv", "r");
		$output = fopen("../productionData/ip.sql", "w");

		if ($test === true) {
			$input = fopen("../testData/ipTest.csv", "r");
			$output = fopen("../testData/ipTest.sql", "w");
		}

		if ($input !== false) {

			$data = fgetcsv($input, 1000, ","); // skip the first row because of headers
		  
			while (($data = fgetcsv($input, 1000, ",")) !== false) {

				// one test runs, let's break after one hundred lines have been created
				if ($test && $row > 100)
					break;

				if (!$test) {
					$longIpRange = $this->cidrToRange($data[0]);
					$correspondingLocationId = $this->binarySearch($longIpRange, $row);
				} else
					$correspondingLocationId = $this->binarySearch($data[0], $row);
				

				$sql = "INSERT INTO IPAddress (Id, Network, GeonameId, ContinentCode, ContinentName, CountryISOCode, CountryName, isAnonymousProxy, isSatelliteProvider, LocationId) \nVALUES ({$row}, '{$data[0]}', {$data[1]}, '{$data[2]}', '{$data[3]}', '{$data[4]}', '{$data[5]}', {$data[6]}, {$data[7]}, {$correspondingLocationId});\n\n";

				$row++;
				fwrite($output, $sql);
			}

			// free up resources
		  	fclose($input);
		  	fclose($output);
		} else {
			echo $test === true ? "\nIP test data not found" : "\nIP production data not found" ;
		}
	}

	/*
	This function takes in a IPv4 String value and returns a long range using bit masking
	The code was taken from https://stackoverflow.com/questions/4931721/getting-list-ips-from-cidr-notation-in-php
	Input:
		String ip address (convert from String first)
	Output:
		Returns the ipRange
	*/
	function cidrToRange($cidr) {
	  	$range = array();
		$cidr = explode('/', $cidr);
		$range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
		$range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
		return $range;
	}

	/*
	This function searches for the appropriate location that associates with this addresses
	We use binary search to reduce runtime from O(N) to O(logN) -- a significant improvement in large datasets
	Input:
		Long ip address range - first value is low end, second value is high end
	Output:
		Int location id
	*/
	function binarySearch($ip4Range, $row) {

		$lo = 0;
		$hi = sizeof($this->minIp);

		while ($lo <= $hi) {

			$mid = (int) ($lo + ($hi - $lo) / 2); // prevents overflow! (rather than (lo + hi) / 2)

			if ($this->minIp[$mid] === $ip4Range[0] && $this->maxIp[$mid] === $ip4Range[1])
				return $mid + 1;
			# we are less than the current point
			else if ($this->minIp[$mid] < $ip4Range[0])
				$lo = $mid + 1;
			else if ($this->maxIp[$mid] > $ip4Range[1])
				$hi = $mid - 1;
			else {
				echo "\nSome problem in binary search OR IPv4 value at row {$row}";
				break;
			}
		}

		return $lo + 1; // +1 because the ID starts at 1 (not zero)
	}

	/*
	This function is a slightly modified binary search
	Input:
		Long ip address (somewhere in the range)
	Output:
		Int location id -- that the ip belongs in the range
	*/
	function binaryTest($ip4, $row) {
		$lo = 0;
		$hi = sizeof($this->minIp);

		while ($lo <= $hi) {

			$mid = (int) ($lo + ($hi - $lo) / 2); // prevents overflow! (rather than (lo + hi) / 2)

			if ($this->minIp[$mid] <= $ip4 && $this->maxIp[$mid] >= $ip4)
				return $mid + 1;
			# we are less than the current point
			else if ($this->minIp[$mid] < $ip)
				$lo = $mid + 1;
			else if ($this->maxIp[$mid] > $ip)
				$hi = $mid - 1;
			else {
				echo "\nSome problem in binary search OR IPv4 value at row {$row}";
				break;
			}
		}

		return $lo + 1; // +1 because the ID starts at 1 (not zero)
	}
}

$obj = new createSql();

// let's determine whether we are testing or writing finished code
if ($argc > 1) {
  	if ($argv[1] == 'test') {
	    $obj->buildSQL(true);
	}
	elseif($argv[1] == 'prod') {
		$obj->buildSQL(false);
	}
} else {
  echo "no argument passed -- please type either of the following: \n";
  echo "php createIPSQL.php test\n";
  echo "php createIPSQL.php prod\n";
}