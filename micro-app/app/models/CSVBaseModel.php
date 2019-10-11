<?php

/**
 * 
 */
class CSVBaseModel extends mysqli
{
	private $success = true;
	public function __construct()
	{
		parent::__construct("localhost", "root", "", "csv");
		if ($this->connect_error) {
			echo ('Fail to connect to DataBase');
		}
	}

	public function getSavedData()
	{
		$output = [];
		$query = "SELECT op.*, sub.* FROM operations op JOIN subscriptions sub ON sub.SubscriptionNumber = op.SubscriptionNumber ORDER BY op.idOperation ASC LIMIT 50"; //JUST TO LIMIT RESULTS INTO JSQRID, OTHERWISE 100k LIGNE AT ONCE IS TOO MUCH FOR THE BROWSER TO RENDER. ONETHER SOLUTION WAS CREATED IN views/home/import.php TO SHOW ALL DATA AT ONCE.
		$result = $this->query($query, MYSQLI_USE_RESULT);
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$output[] = array(
				'account' => $row['AccountNumber'],
				'invoice' => $row['InvoiceNumber'],
				'subscriber' => $row['SubscriptionNumber'],
				'date' => $row['Date'],
				'time' => $row['Time'],
				'real_consumption' => $row['RealVolume'] . $row['RealDuration'],
				'billed_consumption' => $row['BilledVolume'] . $row['BilledDuration'],
				'details' => $row['Details'],
			);
		}
		return $output;
	}

	public function saveSubscriptions($data)
	{
		$query = "INSERT INTO subscriptions (SubscriptionNumber, AccountNumber, InvoiceNumber) VALUES " . $data;
		$this->success = $this->query($query);
		if (!$this->success) {
			echo $this->error;
		}
		return $this->success;
	}

	public function saveAccount($data)
	{
		$query = "INSERT INTO account (AccountNumber) VALUES " . $data;
		$this->success = $this->query($query);
		if (!$this->success) {
			echo $this->error;
		}
		return $this->success;
	}

	public function saveInvoice($data)
	{
		$query = "INSERT INTO invoice (InvoiceNumber) VALUES " . $data;
		$this->success = $this->query($query);
		if (!$this->success) {
			echo $this->error;
		}
		return $this->success;
	}

	public function saveCallOperations($data)
	{
		$query = "INSERT INTO operations (Date, Time, RealDuration, BilledDuration, Details, SubscriptionNumber) VALUES " . $data;
		$this->success = $this->query($query);
		if (!$this->success) {
			echo $this->error;
		}
		return $this->success;
	}

	public function saveInternetOperations($data)
	{
		$query = "INSERT INTO operations (Date, Time, RealVolume, BilledVolume, Details, SubscriptionNumber) VALUES " . $data;
		$this->success = $this->query($query);
		if (!$this->success) {
			echo $this->error;
		}
		return $this->success;
	}

	public function saveSmsOperations($data)
	{
		$query = "INSERT INTO operations (Date, Time, Details, SubscriptionNumber) VALUES " . $data;
		$this->success = $this->query($query);
		if (!$this->success) {
			echo $this->error;
		}
		return $this->success;
	}

	/**
	 * $date [date from]
	 * @return array
	 */
	public function getCallDuration($date = "2012-02-15")
	{
		$query = "SELECT SEC_TO_TIME( SUM( TIME_TO_SEC(Time) ) ) AS total_time FROM operations WHERE RealDuration IS NOT NULL AND Date >= " . $date;
		if (!$this->query($query)) {
			echo $this->error;
			return ["total_time" => ""];
		} else {
			$result = $this->query($query)->fetch_array(MYSQLI_ASSOC);
			return $result;
		}
	}

	/**
	 * @return array
	 */
	public function getSmsNumber()
	{
		$query = "SELECT COUNT(IdOperation) AS total_sms FROM operations WHERE RealDuration IS NULL AND RealVolume IS NULL";
		if (!$this->query($query)) {
			echo $this->error;
			return ["total_sms" => ""];
		} else {
			$result = $this->query($query)->fetch_array(MYSQLI_ASSOC);
			return $result;
		}
	}

	/**
	 * $timeSup time
	 * $timeInf time
	 * @return array
	 */
	public function getTopFacturedDataPerSubscriber($timeSup, $timeInf)
	{
		$top_10 = [];
		$query = "SELECT DISTINCT (sub.SubscriptionNumber), "
				. "GROUP_CONCAT(op.BilledVolume ORDER BY BilledVolume DESC) AS top_10 "
				. "FROM subscriptions sub "
				. "JOIN operations op ON op.SubscriptionNumber = sub.SubscriptionNumber "
				. "WHERE op.BilledVolume IS NOT NULL "
				. "AND NOT (op.Time BETWEEN '" . $timeSup . "' AND '" . $timeInf . "') "
				. "GROUP BY sub.SubscriptionNumber";

		$result = $this->query($query, MYSQLI_USE_RESULT); 
		while ($subscriber = $result->fetch_array(MYSQLI_ASSOC)) {
			$top_10[$subscriber['SubscriptionNumber']] = array_slice(explode(",", $subscriber["top_10"]), 0, 10);
		}
		return $top_10;
	}

	public function getRegisteredInvoices(){
		$query = "SELECT InvoiceNumber FROM invoice";
		$result = $this->query($query, MYSQLI_USE_RESULT);
		while ($row = $result->fetch_array(MYSQLI_NUM)) {
			$invoices[] = $row[0];
		}
		return $invoices;
	}

	public function getRegisteredAccounts(){
		$query = "SELECT AccountNumber FROM account";
		$result = $this->query($query, MYSQLI_USE_RESULT);
		while ($row = $result->fetch_array(MYSQLI_NUM)) {
			$accounts[] = $row[0];
		}
		return $accounts;
	}

	public function getRegisteredSubscriptions(){
		$query = "SELECT SubscriptionNumber FROM subscriptions";
		$subscriptions = $this->query($query, MYSQLI_USE_RESULT);
		while ($row = $subscriptions->fetch_array(MYSQLI_NUM)) {
			$accounts[] = $row[0];
		}
		return $accounts;
	}
}