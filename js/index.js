function getRoom()
{

    var payload = {};

    payload['userID'] = (localStorage.userID ? localStorage.userID : "");
    payload['sessionKey'] = (localStorage.sessionID ? localStorage.sessionID : "");
    payload['roomCode'] = document.getElementById('enterCodeBox').value;


    var jsonPayload = JSON.stringify(payload);

    //window.alert(jsonPayload);

    var url = "/API/GetRoomByCode.php";

    var xhr = new XMLHttpRequest();

    xhr.open("POST", url, true);	//true associates with asyncrous
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

                //window.alert(jsonObject.questions.length);

                if(jsonObject.error == "")
                {
                    localStorage.title = jsonObject.title;
                    localStorage.expirationTime = jsonObject.expire;
                    localStorage.pollID = jsonObject.id;
                    localStorage.questions = JSON.stringify (jsonObject.questions)
                    window.location.href = "/getPoll.html";

                }else if (jsonObject.errorCode == 1)
                {
                    //window.alert("Please log in to view this poll.");
                }else if (jsonObject.errorCode == 2)
                {
                    //window.alert("Poll is not live yet.");
                }

            }
            else
            {
                return;
            }
        }
    }

    xhr.send(jsonPayload);
}
