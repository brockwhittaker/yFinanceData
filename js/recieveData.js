function formRequest (ticker, startDate, endDate) {
	return "ticker=" + ticker.replace(/ /g, "") + "&startdate=" + startDate + "&enddate=" + endDate;
}

function parseAndProcess (data) {
	data = JSON.parse(data);
	data = processDateLines(data);
	return data;
}

function processDateLines (data) {
	for (x in data.tickers) {
		for (var y = 0; y < data.tickers[x].data.length; y++) {
			dailyStats = data.tickers[x].data[y].split(/,/g).map(function (n) {
				return parseFloat(n);
			});
			
			data.tickers[x].data[y] = {
				"Date": dailyStats[0],
				"Open": dailyStats[1],
				"High": dailyStats[2],
				"Low": dailyStats[3],
				"Close": dailyStats[4],
				"Volume": dailyStats[5],
				"Adj. Close": dailyStats[6],
			}
		}
	}

	return data;
}