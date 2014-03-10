<?php
// GET ISBN from search box
if (isset($_GET['isbn']) && isset($_GET['formBuySell'])) {
	$isbn = $_GET['isbn'];
	$buy_sell = $_GET['formBuySell'];
	if (!empty($isbn)) {
		// User is buying, so search sell postings
		if ($buy_sell == 'buy') {
			$query = "SELECT userSelling, price, quality, accessCode, cd FROM selling WHERE isbnSelling = '".mysql_real_escape_string($isbn)."' ORDER BY sellPostId DESC";
			if ($query_run = mysql_query($query)) {
				// Invalid numerical input (ISBN must be 10 or 13 digits)
				if ((strlen((string)$isbn) != 13) && (strlen((string)$isbn) != 10)) {
					echo 'Please enter a valid ISBN-13 or ISBN-10 number.';
				// No users selling that book	
				} else if (mysql_num_rows($query_run) == NULL) {
					echo 'Sorry! No one\'s selling '.$isbn.', but you can let everyone know you\'re buying it.';
				// Display search results
				} else {
					echo 'Seller Price Quality Access Code CD<br>';
					while ($query_row = mysql_fetch_assoc($query_run)) {
						$user_selling = $query_row['userSelling']; // 13 digit number, NOT NULL
						$price = ($query_row['price'] == NULL) ? '-' : $query_row['price']; // $xxx.xx, can be NULL
						$quality = $query_row['quality']; // 1-5, NOT NULL
						$access_code = $query_row['accessCode']; // Boolean value, NOT NULL
						$cd = $query_row['cd']; // Boolean value, NOT NULL
						// Should be formatted as a dynamic length list/table...
						echo $user_selling.' '.$price.' '.$quality.' '.$access_code.' '.$cd.'<br>';
					}
				}
			// Non-numerical input
			} else {
				echo $isbn.' is not a valid ISBN.'; // mysql_error();
			}
		// User is selling, so search buy postings
		} else if ($buy_sell == 'sell') {
			$query = "SELECT userBuying, price FROM buying WHERE isbnBuying = '".mysql_real_escape_string($isbn)."' ORDER BY buyPostId DESC";
			if ($query_run = mysql_query($query)) {
				// Invalid numerical input (ISBN must be 10 or 13 digits)
				if ((strlen((string)$isbn) != 13) && (strlen((string)$isbn) != 10)) { 
					echo 'Please enter a valid ISBN-13 or ISBN-10 number.';
				// No users buying that book	
				} else if (mysql_num_rows($query_run) == NULL) {
					echo 'Sorry! No one\'s buying '.$isbn.', but you can let everyone know you\'re selling it.';
				// Display search results
				} else {
					echo 'Buyer Price<br>';
					while ($query_row = mysql_fetch_assoc($query_run)) {
						$user_buying = $query_row['userBuying']; // 13 digit number, NOT NULL
						$price = ($query_row['price'] == NULL) ? '-' : $query_row['price']; // $xxx.xx, can be NULL
						// Should be formatted as a dynamic length list/table...
						echo $user_buying.' '.$price.'<br>';
					}
				}
			// Non-numerical input
			} else {
				echo $isbn.' is not a valid ISBN.';
			}
		// formBuySell equals something other than buy or sell (malformed URL)
		} else {
			echo 'Invalid buy/sell choice.';
		}
	// NULL input
	} else {
		echo 'You must enter an ISBN to search.';
	}
}

?>

<!--I used GET for easier site browsing-->
<form action="<?php echo $current_file;?>" method="GET">
I'm looking to 
<!--Buy/sell dropdown-->
<select name="formBuySell">
	<option value="buy">buy</option>
	<option value="sell">sell</option>
</select>
<!--Search box-->
ISBN: <input type="text" name="isbn">
<input type="submit" value="Find it!">
</form>