#yFinanceData

###Description
A library for extracting and processing equity data from Yahoo! Finance.

###Implementation
Yahoo! Finance does not allow users to pull directly via AJAX requests, so an intermediary server-side language is required. The PHP file `getTickers.php` accepts input via POST variables:

1. `ticker` - a comma seperated list of all tickers you want to get data for (eg. "AAPL,GOOGL,MSFT,TSCO").
2. `startdate` - this is the first date that you want prices for (furthest back date). This should be in the format "MM-DD-YYYY".
3. `enddate` - this is the latest date that you want prices for. This should also be in the "MM-DD-YYYY" format. If you want current prices, feel free to put in a future date as Yahoo! Finance handles that by just giving all price data up until the current date.

In the JavaScript file `recieveData.js` you can easily produce this string by using the `formRequest` function with parameters `ticker`, `startDate`, and `endDate`.

```javascript
function formRequest (ticker, startDate, endDate) {
	return "ticker=" + ticker.replace(/ /g, "") + "&startdate=" + startDate + "&enddate=" + endDate;
}

formRequest ("AAPL,GOOGL,MSFT,TSCO", "01-01-2005", "01-01-2015");
// ticker=AAPL,GOOGL,MSFT,TSCO&startdate=01-01-2005&enddate=01-01-2015
```

Then send an AJAX request to the server-side PHP with the POST header created by formRequest. An example with `nanoajax.js`, which is in the vendor section of this repo is:

```javascript
nanoajax.ajax({url: "php/getTickers.php", method: 'POST', body: formRequest("AAPL,SPY", "01-15-1980", "07-24-2015")}, function (code, responseText, request) {
	data = parseAndProcess(responseText);
});
```


The data that you will recieve will be in JSON format with the following structure:

```javascript
[objectName] = {
  metadata: {
    endDate: {
      0: "01",
      1: "01",
      2: "2015"
    },
    startDate: {
      0: "01",
      1: "01",
      2: "2005"
    },
    failedToRetrieve: 0,
    successfullyRetreived: 3,
    tickers: ["INTC", "AAPL", "MSFT"],
    webAddress: "http://real-chart.finance.yahoo.com/table.csv?s=[ticker]&d=0&e=01&f=2015&g=d&a=0&b=01&c=2005&ignore=.csv"
  },
  tickers: {
    "AAPL" : {
      "data" : [data]
    },
    "INTC" : {
      "data" : [data]
    },
    "MSFT" : {
      "data" : [data]
    }
  }
}
```

The sections are broken down into days in each ticker's data. Each day should have the following data in JSON form:

```javascript
{
  "Date": "01-01-2015",
  "Open":124.940002,
  "High":126.230003,
  "Low":124.849998,
  "Close":126,
  "Volume":27900200,
  "Adj. Close":126
}
```

The values are mostly floats so there's no need for parsing. The only non-floats are volume (expressed in integer of full trades), and the date (a string in MM-DD-YYYY format).

###Demo
A full working demo that is the same as `test.html` is currently live [on my site](http://www.lavancier.com/yFinance/test.html).
To check out the results, go into console and check out the variable `data`.

Any questions or concerns, email me at [brock@lavancier.com](brock@lavancier.com).
