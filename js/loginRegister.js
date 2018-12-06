// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


function logout()
{
    localStorage.clear();
    location.reload(true);
}

function hideOrShow(elementId, showState) {
    var visible = "visible";
    var display = "block";
    if (!showState) {
        visible = "hidden";
        display = "none";
    }

    document.getElementById(elementId).style.visibility = visible;
    document.getElementById(elementId).style.display = display;
}

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

function regexCheck(str, reg) {
    return reg.test(str);
}

function doLogin() {

    logUsername = document.getElementById("userName").value     //obtain username from login entry bar
    logPassword = document.getElementById("password").value     //obtain password from login entry bar

    /*
    if(regexCheck(logUsername, /[^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$)]/))
    {
        //add div saying username is not valid email
    }

    if(regexCheck(logPassword, /"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"/))
    {
        //add div saying password is not valid password
    }
    */

    //session ID

    //sha256
    hashedPassword = sha256(logPassword);

    //Payload
    var userLog = {};

    userLog["userEmail"] = logUsername;
    userLog["password"] = hashedPassword;

    var jsonPayload = JSON.stringify(userLog);
    //get register PHP page name
    var url = "/API/Login.php";
    var xhr = new XMLHttpRequest();

    xhr.open("POST", url, true);    //true associates with asyncrous
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    // xhr.onreadystatechange is called when xhr.send recieves a response
    // readyState == 4 means done with XMLHttpRequest
    // status == 200 is a successful request once finished
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {


                var jsonObject = JSON.parse(xhr.responseText);
                error = jsonObject.error;

                if(error == ""){
                    localStorage.userID = jsonObject.id;
                    localStorage.firstName = jsonObject.firstName;
                    localStorage.lastName = jsonObject.lastName;
                    localStorage.orgID = jsonObject.optionalName;
                    localStorage.dateCreated = jsonObject.dateCreated;
                    localStorage.sessionID = jsonObject.sessionID;
                    error = jsonObject.error;
                    location.reload(true);
                }

                else{
                    document.getElementById("userName").text = "Invalid username/password";
                    return
                }

            }
            else {
                return;
            }
        }
    }

    xhr.send(jsonPayload);
}

function doRegister() {
    userId = 0;
    sessionId = 0;

    var userEntry = {};

    regUsername = document.getElementById("regUser").value;
    regPassword = document.getElementById("regPass").value;
    regRePassword = document.getElementById("regRePass").value;
    regOrgID = document.getElementById("regOrgID").value;
    firstName = document.getElementById("firstName").value;
    lastName = document.getElementById("lastName").value;

    /*
    if (regexCheck(regUsername, /[^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$)]/)) {
        document.getElementById("validation").innerHTML = "Not a valid Email";
        return;
    }

    if (regexCheck(regPassword, /"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"/)) {
        //div for not valid password
    }
    if (regPassword != regRePassword) {
        console.log("password mismatch");
        return;
    }
    */

    //sha256 password
    hashedPassword = sha256(regPassword);

    //Session ID
    sessionID = Math.random().toString(36).substr(2, 10);
    //Payload
    userEntry["firstName"] = firstName;
    userEntry["lastName"] = lastName;
    userEntry["optionalName"] = regOrgID;
    userEntry["userEmail"] = regUsername;
    userEntry["password"] = hashedPassword;

    var jsonPayload = JSON.stringify(userEntry);
    //get register PHP page name
    var url = "/API/Register.php";
    var xhr = new XMLHttpRequest();

    xhr.open("POST", url, true);    //true associates with asyncrous
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    // xhr.onreadystatechange is called when xhr.send recieves a response
    // readyState == 4 means done with XMLHttpRequest
    // status == 200 is a successful request once finished
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);

                error = jsonObject.error;

                //if error
            }
            else {
                return;
            }
        }
    }

    xhr.send(jsonPayload);

}
