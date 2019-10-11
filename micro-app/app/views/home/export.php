<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<html>  
    <head>  
        <title>CSV Processing</title>  
    </head>  
    <body>
        <div style="margin: 2% 10%">
            <input type="button" class="btn btn-primary" name="return" value="Home" onclick="window.location.href='../home/index'" style="float: right;">
        </div>  
        <div class="container">  
            <br />
            <div class="table-responsive">  
                <h3 align="center">Display values</h3><br />
                <div id="grid_table"></div>
            </div>  
        </div>
    </body>  
</html>  
<script>
 
    $('#grid_table').jsGrid({

        width: "100%",
        height: "100%",

        filtering: false,
        inserting:false,
        editing: false,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 20,
        pageButtonCount: 5,
        deleteConfirm: "Do you really want to delete data?",

        controller: {
            loadData: function(filter){
                return $.ajax({
                    type: "GET",
                    url: "../home/getSavedData",
                    data: filter
                });
            },
        },

        fields: [
            {
                name: "account", 
                type: "text", 
                width: 70,
            },
            {
                name: "invoice", 
                type: "text", 
                width: 70,
            },
            {
                name: "subscriber", 
                type: "text", 
                width: 70,
            },
            {
                name: "date",
                type : "text",
                width: 70,
            },
            {
                name: "time",
                type : "text",
                width: 70,
            },
            {
                name: "real_consumption",
                type : "text",
                width: 85,
            },
            {
                name: "billed_consumption",
                type : "text",
                width: 85,
            },
            {
                name: "details", 
                type: "text", 
                width: 60,
            },
            {
                type: "control"
            }
        ]

    });

</script>