<?php
// get quotes from Y!Finance

$tickerArr = explode(",", $_POST['ticker']);
$startDate = explode("-", $_POST['startdate']); // month-date-year
$endDate = explode("-", $_POST['enddate']); // month-date-year

$successCounter = 0;
$failureCounter = 0;

function getTickers ($ticker) {
	$website = "http://real-chart.finance.yahoo.com/table.csv?s=" . $ticker . "&d=" . $enddate[0] . "&e=" . $startDate[1] . "&f=" . $startDate[2] . "&g=d&a=" . $startDate[0] . "&b=" . $startDate[1] . "&c=" . $startDate[2] . "&ignore=.csv";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $website);
	curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$output = curl_exec($ch);
	curl_close($ch);
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/javascript');
	return substr($output, 42);
}


$tickerData = array();
for ($x = 0; $x < count($tickerArr); $x++) {
	try {
		$tickerData[$tickerArr[$x]] = array(
			"data" => preg_split('/\r\n|\n|\r/', getTickers($tickerArr[$x]))
		);
		$successCounter++;
	} catch (Exception $e) {
		$tickerData[$tickerArr[$x]] = array(
			"data" => null
		);
		$failureCounter++;
	}
}

$metaData = array(
	"tickers" => $tickerArr,
	"startDate" => array(
		"month" => $startDate[0],
		"date" => $startDate[1],
		"year" => $startDate[2],
	),
	"endDate" => $endDate,
	"successfullyRetrieved" => $successCounter,
	"failedToRetrieve" => $failureCounter
);

echo json_encode(
	array(
		"metadata" => $metaData,
		"tickers" => $tickerData
	)
);
?>