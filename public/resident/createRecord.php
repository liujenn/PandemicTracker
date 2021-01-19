<?php require_once('../../private/initialize.php'); ?>

<?php
if(isset($_POST['Submit'])){
    if($_SESSION['Active'] == false){
        header("location:../../index.php");
        exit;
    }
    
    $Rid = get_rid($_SESSION["uname"]);
    date_default_timezone_set("America/Vancouver");
    // Retrieve all the entered data
    $health_detail = [];
    $risk_detail = [];

    $health_detail['Date'] = $_POST['txt_date'] ?? '';
    $health_detail['Rid'] = $Rid;
    $health_detail['Temperature'] = $_POST['txt_temp'] ?? '';
    $health_detail['ifFlu'] = $_POST['check_flu'] ?? '';
    $risk_detail['Date'] = $_POST['txt_date'] ?? '';
    $risk_detail['Rid'] = $Rid;
    $risk_detail['ifExp'] = $_POST['check_exp'] ?? '';
    $risk_detail['ifNeigh'] = $_POST['check_neigh'] ?? '';
    $risk_detail['ifClose'] = $_POST['check_close'] ?? '';
        
    if (create_health($health_detail) == true && create_risk($risk_detail) == true){
        if (check_quarantine($health_detail['Date'], $Rid) == true){
            echo "Your record for " . $health_detail['Date'] . " has been saved.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
        <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous">
        </script>
        <style>
        form{
            display:flex;
            /* justify-content:center; */
            /* align-items: center; */
            flex-direction: column;
        }
        .combo{
            display:flex;
            flex-direction: column;
            /* justify-content:center; */
            /* align-items:center; */
            margin-bottom:10px;
        }
        .section{
            padding-left:50px;
        }
        input[type="text"] {
            width: 250px;
        }
        .submitBtn, .backBtn{
            width:200px;
        }
        </style>
	</head>
	<body>
		<form action="createRecord.php" method="post">
            <h1>Intake Form</h1>
            <h2>Section A: Health Status</h2>
            <div class="section">
                <div class="combo">
                    <span>1. What is today's date? (eg. 1999-01-30)</span>
                    <input type="text" name="txt_date" placeholder="1999-01-30"/>
                </div>
                <div class="combo">
                    <span>2. What is your temperature?</span>
                    <input type="text" name="txt_temp" placeholder="Temperature"/>
                </div>
                <div class="combo">
                    <span>3. Any flu symptoms?</span>
                    <div >
                        <label for="yesFlu">Yes</label>
                            <input type="checkbox" id="yesFlu" name="check_flu" value='1' style="margin-right:25px"/>
                        <label for="noFlu">No</label>
                            <input type="checkbox" id="noFlu" name="check_flu" value='0'/>
                    </div>
                </div>
            </div>
            <h2>Section B: Risk Status</h2>
            <div class="section">
                <div class="combo">
                    <span>1. Exposure to confirmed case?</span>
                    <div>
                        <label for="yesExp">Yes</label>
                            <input type="checkbox" id="yesExp" name="check_exp" value='1' style="margin-right:25px"/>
                        <label for="noExp">No</label>
                            <input type="checkbox" id="noExp" name="check_exp" value='0'/>
                    </div>
                </div>
                <div class="combo">
                    <span>2. Confirmed case in neighbourhood?</span>
                    <div>
                        <label for="yesNeigh">Yes</label>
                            <input type="checkbox" id="yesNeigh" name="check_neigh" value='1' style="margin-right:25px"/>
                        <label for="noNeigh">No</label>
                            <input type="checkbox" id="noNeigh" name="check_neigh" value='0'/>
                    </div>
                </div>
                <div class="combo">
                    <span>3. Close contact has flu-like symptoms?</span>
                    <div>
                        <label for="yesClose">Yes</label>
                            <input type="checkbox" id="yesClose" name="check_close" value='1' style="margin-right:25px"/>
                        <label for="noClose">No</label>
                            <input type="checkbox" id="noClose" name="check_close" value='0'/>
                    </div>
                </div>
            </div>
            <div style="display:flex;">
                <input type="submit" class="submitBtn" name = "Submit"/>
                <input type="button" class="backBtn" onclick="goBack()" value="Back">
            </div>
        </form>
        <script>
            function goBack(){
                window.location.href="./index.php"
            }
            
            $("#yesFlu").click(function(){
                if($('#noFlu').prop('checked')){
                    $("#noFlu").prop("checked",false);
                }
            });
            $("#noFlu").click(function(){
                if($('#yesFlu').prop('checked')){
                    $("#yesFlu").prop("checked",false);
                }
            });
            $("#yesExp").click(function(){
                if($('#noExp').prop('checked')){
                    $("#noExp").prop("checked",false);
                }
            });
            $("#noExp").click(function(){
                if($('#yesExp').prop('checked')){
                    $("#yesExp").prop("checked",false);
                }
            });
            $("#yesNeigh").click(function(){
                if($('#noNeigh').prop('checked')){
                    $("#noNeigh").prop("checked",false);
                }
            });
            $("#noNeigh").click(function(){
                if($('#yesNeigh').prop('checked')){
                    $("#yesNeigh").prop("checked",false);
                }
            });
            $("#yesClose").click(function(){
                if($('#noClose').prop('checked')){
                    $("#noClose").prop("checked",false);
                }
            });
            $("#noClose").click(function(){
                if($('#yesClose').prop('checked')){
                    $("#yesClose").prop("checked",false);
                }
            });
            
        </script>
	</body>
</html>


<?php include(PRIVATE_P . '/footer.php'); ?>
