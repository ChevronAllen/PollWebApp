
var qNumber = 25;
document.getElementById("question#").innerHTML = qNumber;
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function hideOrShow(elementId, showState)
{
	var visible = "visible";
	var display = "block";
	if(!showState)
	{
		visible = "hidden";
		display = "none";
	}

	document.getElementById(elementId).style.visibility = visible;
	document.getElementById(elementId).style.display = display;
}

function addTextBox()
{
	var input = document.createElement('input');
	input.type = 'text';	
}

$(document).ready(function(){
    var next = 1;
    $(".add-more").click(function(e){
        e.preventDefault();
        var addto = "#field" + next;
        var addRemove = "#field" + (next);
        next = next + 1;
        var newIn = '<input autocomplete="off" class="input form-control" id="field' + next + '" name="field' + next + '" type="text">';
        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button></div><div id="field">';
        var removeButton = $(removeBtn);
        $(addto).after(newInput);
        $(addRemove).after(removeButton);
        $("#field" + next).attr('data-source',$(addto).attr('data-source'));
        $("#count").val(next);  
        
            $('.remove-me').click(function(e){
                e.preventDefault();
                var fieldNum = this.id.charAt(this.id.length-1);
                var fieldID = "#field" + fieldNum;
                $(this).remove();
                $(fieldID).remove();
            });
    });
    

    
});

var userId = 0;
var firstName = '';
var lastName = '';
var orgID = '';
var dateCreated = '';
var sessionID = 0;
var error = '';

var hashedPassword;
//variables for login
var logUsername;
var logPassword;
//variables for register
var regUsername;
var regPassword;
var regRePassword;
var regOrgID;

function regexCheck(str, reg)
{
    return reg.test(str);
}

function doLogin()
{
    logUsername = document.getElementById("userName").value     //obtian username from login entry bar
    logPassword = document.getElementById("password").value //obtain password from login entry bar

    if(regexCheck(logUsername, /[^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$)]/))
    {
        //add div saying username is not valid email
    }

    if(regexCheck(logPassword,/"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"/))
    {
        //add div saying password is not valid password
    }
    //session ID

    //sha256
    hashedPassword = sha256(logPassword);

    //Payload
    var userLog = {};

    userLog["userName"] = logUsername;
    userLog["password"] = hashedPassword;

    var jsonPayload = JSON.stringify(userEntry);
    //get register PHP page name
    var url = "../API/Login.php";
    var xhr = new XMLHttpRequest();

    xhr.open("POST", url, true);    //true associates with asyncrous
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    // xhr.onreadystatechange is called when xhr.send recieves a response
    // readyState == 4 means done with XMLHttpRequest
    // status == 200 is a successful request once finished
    xhr.onreadystatechange = function()
    {
        if (this.readyState == 4)
        {
            if(this.status == 200)
            {
                var jsonObject = JSON.parse(xhr.responseText);

                userID = jsonObject.id;
                firstName = jsonObject.firstName;
                lastName = jsonObject.lastName;
                orgID = jsonObject.optionalName;
                dateCreated = jsonObject.dateCreated;
                sessionID = jsonObject.sessionID;
                error = jsonObject.error;

                //if error return;

            }
            else
            {
                return;
            }
        }
    }

    xhr.send(jsonPayload);
}

function doRegister()
{
    userId = 0;
    sessionId = 0;

    var userEntry = {};

    regUsername = document.getElementById("id01.regUser").value;
    regPassword = document.getElementById("id01.regPass").value;
    regRePassword = document.getElementById("id01.regRePass").value;
    regOrgID = document.getElementById("id01.regOrgId").value;
    firstName = document.getElementById("id01.firstName").value;
    lastName = document.getElementById("id01.lastName").value;

    if(regexCheck(regUsername, /[^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$)]/))
    {
        document.getElementById("validation").innerHTML = "Not a valid Email";
        return;
    }

    if(regexCheck(regPassword, /"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"/))
    {
        //div for not valid password
    }
    if(regPassword != regRePassword)
    {
        console.log("password mismatch");
        return;
    }

    //sha256 password
    hashedPassword = sha256(regPassword);

    //Session ID
    sessionID = Math.random().toString(36).substr(2,10);
    //Payload
    userEntry["firstName"] = firstName;
    userEntry["lastName"] = lastName;
    userEntry["regOrgID"] = regOrgID;
    userEntry["regUsername"] = regUsername;
    userEntry["regPassword"] = hashedPassword;

    var jsonPayload = JSON.stringify(userEntry);
    //get register PHP page name
    var url = "../API/Register.php";
    var xhr = new XMLHttpRequest();

    xhr.open("POST", url, true);    //true associates with asyncrous
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    // xhr.onreadystatechange is called when xhr.send recieves a response
    // readyState == 4 means done with XMLHttpRequest
    // status == 200 is a successful request once finished
    xhr.onreadystatechange = function()
    {
        if (this.readyState == 4)
        {
            if(this.status == 200)
            {
                var jsonObject = JSON.parse(xhr.responseText);

                error = jsonObject.error;
                
                //if error
            }
            else
            {
                return;
            }
        }
    }

    xhr.send(jsonPayload);

}
