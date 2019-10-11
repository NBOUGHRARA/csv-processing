<?php

/**
 * 
 */
class Home extends Controller
{

	private $CSVService;
	private $CSVBaseModel;
	private $success = true;

	public function __construct()
	{
		$this->CSVService = $this->service('CSVService');
		$this->CSVBaseModel = $this->model('CSVBaseModel');
	}

	public function index()
	{
		$data = [];
		if (isset($_POST['import_csv'])) {
			$this->importFile($_FILES['file']['tmp_name']);
		}
		$this->view('home/index');
	}

	public function importFile($file)
	{
		$file = fopen($file, 'r');

		/*GETTING ALREADY REGISTERED INVOICES, ACOOUNTS AND SUBSCRIBERS*/
		$registeredInvoices = $this->CSVBaseModel->getRegisteredInvoices();
		$registeredAccounts = $this->CSVBaseModel->getRegisteredAccounts();
		$registeredSubscribers['subscriberNumber'] = $this->CSVBaseModel->getRegisteredSubscriptions();

		/*INISIALIZATION*/
		$subscriber['query'] = $subscriber['subscriberNumber'] = [];
		$invoice = $account = [];

		/*TO JUMP THE FIRST 3 LINES*/
		fgetcsv($file, 1000, ';');
		fgetcsv($file, 1000, ';');
		fgetcsv($file, 1000, ';');

		while ($row = fgetcsv($file, 1000, ';')) {
			$date = $this->CSVService->toSqlDateFormat($row[3]);

			/*AN IS_VALID FUNCTION SHOULD BE IMPLEMENTED IN CSV_SERVICE IN ORDER TO VALIDATE ROWS BEFORE SAVE ACCORDING TO COMPANY/CUSTOMER RULES (DATA WILL BE IGNORED OR CHANGED)*/
			if (!$date) {
				$date = $previousDate;
			} else {
				$previousDate = $date;
			}

			//PREPARING DATA TO BE SAVED INTO OPERATIONS TABLE
			//ANY AMBIGUOUS DATA WILL BE INGNORD: VERIFICATION WILL BE BASED ON VOLUME/DURATION
			if ((bool) $row[5]) {
				$data = "('" . implode("', '", [$date, $row[4], $row[5], $row[6], str_replace("'", " ", $row[7]), $row[2]]) . "')";
				if (count(explode(":", $row[5])) > 1 && count(explode(":", $row[6])) > 1) { //(A && B)
					$this->CSVBaseModel->saveCallOperations($data);
				} else if(!(count(explode(":", $row[5])) > 1 || count(explode(":", $row[6])) > 1)) { //!(A || B)
					$this->CSVBaseModel->saveInternetOperations($data);
				}
			} else {
				$this->CSVBaseModel->saveSmsOperations("('" . implode("', '", [$date, $row[4], str_replace("'", " ", $row[7]), $row[2]]) . "')");
			}

			//PREPARING DATA TO BE SAVED INTO INVOICE TABLE 
			if (!in_array($row[1], $registeredInvoices)) {
				$invoice[] = $row[1]; 
				$registeredInvoices[] = $row[1]; 
			}

			//PREPARING DATA TO BE SAVED INTO ACCOUNT TABLE 
			if (!in_array($row[0], $registeredAccounts)) {
				$account[] = $row[0];
				$registeredAccounts[] = $row[0];
			}

			//PREPARING DATA TO BE SAVED INTO SUBSCRIBERS TABLE 
			if (!in_array($row[2], $registeredSubscribers['subscriberNumber'])) {
				$subscriber['subscriberNumber'][] = $row[2];
				$registeredSubscribers['subscriberNumber'][] = $row[2];
				$subscriber['query'][] = "('" . $row[2] . "', '" . $row[0] . "', '" . $row[1] . "')"; /*PRE-PREPARING QUERY STRING*/
			}
		}

		$data = [
			'invoice' => "(" . implode("), (", $invoice) . ")",
			'account' => "(" . implode("), (", $account) . ")",
			'subscriptions' => implode(",", $subscriber["query"])
		];

		//SAVING DATA INTO TABLES
		foreach ($data as $table => $values) {
			$function = "save" . ucfirst($table);
			$this->success = $this->success && $this->CSVBaseModel->$function($values);
		}
		if ($this->success) {
			echo "Successfully Imported <br>";
		} else {
			echo " <br> Import completed with some ignored lines <br>";
		}
	}

	public function export()
	{
		$this->view('home/export');
	}

	public function getSavedData()
	{	
		/*Only get Method allowed*/
		if ($_SERVER["REQUEST_METHOD"] == "GET") {
			$response = $this->CSVBaseModel->getSavedData();
			header("Content-Type: application/json");
			echo json_encode($response);
		}
	}

	public function queries()
	{
		$data = [];
		/*SUM OF CALL DURATION BEFORE 2012-02-15*/
		$data["callDuration"] = $this->CSVBaseModel->getCallDuration("2012-02-15");
		$data["smsNumber"] = $this->CSVBaseModel->getSmsNumber();
		$data["topFacturedData"] = $this->CSVBaseModel->getTopFacturedDataPerSubscriber("8:00:00", "18:00:00");
		$this->view('home/queries', $data);
	}
}