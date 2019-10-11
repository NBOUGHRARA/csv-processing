<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h2>Custom File</h2>
  <p>Choose a csv file to import, data is available via "View saved Data" and queries are available via "Execute queries" <br> Queries are : <br> 1. Find total real duration fo calls after 15/02/2012 excluded <br> 2. Find total sms sent <br> 3. Find top 10 billed volume of data outside of 8h 18h per user. </p>
  <form action="index" method="Post" enctype="multipart/form-data">
    <p>Custom file:</p>
    <div class="custom-file mb-3">
      <input type="file" class="custom-file-input" id="customFile" name="file">
      <label class="custom-file-label" for="customFile">Choose file</label>
    </div>
  
    <div class="mt-3">
      <input type="submit" class="btn btn-primary" name="import_csv" value="Submit"></button>
      <input type="button" class="btn btn-primary" name="saved_data" id="saved_data" value="View saved data" onclick="window.location.href='../home/export'">
      <input type="button" class="btn btn-primary" name="execute_queries" value="Execute queries" onclick="window.location.href='../home/queries'">
    </div>
  </form>
</div>

<script>
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

</body>
</html>
