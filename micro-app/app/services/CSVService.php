<?php

/**
 * 
 */
class CSVService
{	

	/*public function fetchImportedData($file)
	{
		$file = fopen($file, 'r');

		//INITIALIZATION
		$subscriber = $account = $invoice = $operations = [];
		$account['accountNumber'] = $account['query'] = $invoice['invoiceNumber'] = $invoice['query'] = [];

		//TO JUMP THE FIRST 3 LINES
		fgetcsv($file, 1000, ';');
		fgetcsv($file, 1000, ';');
		fgetcsv($file, 1000, ';');

		while ($row = fgetcsv($file, 1000, ';')) {
			$date = $this->toSqlDateFormat($row[3]);

			//PREPARING DATA TO BE SAVED INTO INVOICE TABLE 
			if (!in_array($row[1], $invoice[])) {
				$invoice[] = $row[1]; 
				$invoice['query'][] = "('" . $row[1] . "', '" . $row[0] . "')";
			}

			//PREPARING DATA TO BE SAVED INTO SUBSCRIBERS TABLE 
			if (!in_array($row[2], $subscriber)) {
				$subscriber[] = $row[2];
			}

			//PREPARING DATA TO BE SAVED INTO ACCOUNT TABLE 
			if (!in_array($row[0], $account['accountNumber'])) {
				$account['accountNumber'][] = $row[0];
				$account['query'][] = "('" . $row[0] . "', '" . $row[2] . "')";
			}

			//PREPARING DATA TO BE SAVED INTO OPERATION TABLE
			if ((bool) $row[5]) {
				if (count(explode(":", $row[5])) > 1) { //PHONE_CALLS
					$operations[] = "('" . implode("', '", [$date, $row[4], NULL, NULL, $row[5], $row[6], $row[7], $row[1]]) . "')";
				} else { //INTERNET
					$operations[] = "('" . implode("', '", [$date, $row[4], $row[5], $row[6], NULL, NULL, $row[7], $row[1]]) . "')";
				}
			} else { //SMS
				$operations[] = "('" . implode("', '", [$date, $row[4], NULL, NULL, NULL, NULL, $row[7], $row[1]]) . "')";
			}
		}
		return [
			'subscriber' => "(" . implode("), (", $subscriber) . ")" ,
			'account' => implode(",", $account["query"]),
			'invoice' => implode(",", $invoice["query"]),
			'operations' => implode(",", $operations)
		];
	}*/

	public function toSqlDateFormat($date)
	{
		$date = explode("/", $date);
		if (checkdate($date[1], $date[0], $date[2])) {
			return $date[2] . "-" . $date[1] . "-" . $date[0];
		} else {
			return false;
		}
	}
}