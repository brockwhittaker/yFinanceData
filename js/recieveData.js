function formRequest (ticker, startDate, endDate) {
	return "ticker=" + ticker.replace(/ /g, "") + "&startdate=" + startDate + "&enddate=" + endDate;
}

function parseAndProcess (data) {
	data = JSON.parse(data);
	data = processDateLines(data);
	return data;
}

function processDateLines (data) {
	var dailyStats;
	
	for (x in data.tickers) {
		for (var y = 0; y < data.tickers[x].data.length; y++) {
			dailyStats = data.tickers[x].data[y].split(/,/g)
			
			data.tickers[x].data[y] = {
				"Date": dailyStats[0],
				"Open": parseFloat(dailyStats[1]),
				"High": parseFloat(dailyStats[2]),
				"Low": parseFloat(dailyStats[3]),
				"Close": parseFloat(dailyStats[4]),
				"Volume": parseInt(dailyStats[5], 10),
				"Adj. Close": parseFloat(dailyStats[6]),
			}
		}
	}

	return data;
}