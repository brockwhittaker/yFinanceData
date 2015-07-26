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
````

Then send an AJAX request to the server-side PHP with the POST header created by formRequest.

```javascript
formRequest ("AAPL,GOOGL,MSFT,TSCO", "01-01-2005", "01-01-2015");
// ticker=AAPL,GOOGL,MSFT,TSCO&startdate=01-01-2005&enddate=01-01-2015
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
    tickers: ["INTC", "AAPL", "MSFT"]
  },
  tickers: {
    "AAPL" : {
      "data" : data
    },
    "INTC" : {
      "data" : data
    },
    "MSFT" : {
      "data" : data
    }
  }
}
```

The sections are broken down into days in each ticker's data. Each day should have the following data in JSON form:

```javascript
{
  "Date":2015,
  "Open":124.940002,
  "High":126.230003,
  "Low":124.849998,
  "Close":126,
  "Volume":27900200,
  "Adj. Close":126
}
```

The values are floats so there' no need for parsing.
