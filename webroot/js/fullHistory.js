function getCreated()
{
    var payload = {};

    payload['userID'] = (localStorage.userID ? localStorage.userID : "");
    payload['sessionID'] = (localStorage.sessionID ? localStorage.sessionID : "");

    var jsonPayload = JSON.stringify(payload);

    var url = "/API/GetFullRoomHistory.php";

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
                

                if(jsonObject.error == ""){
                    var createdRooms = jsonObject.rooms;
                    for(var i = 0; i < createdRooms.length; i++)
                    {
                        addRow(createdRooms[i].roomCode, createdRooms[i].roomID, "");

                    }
                }else{
                    window.alert("Error getting history. Please reload.")
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

function addRow(id, code, title){

    var row = document.createElement("tr");
    row.innerHTML = '<th>' + id + '</th><th>' + code + '</th><th>' + title + '</th>';
    var emptyDiv = document.getElementById("tableBody");
    document.getElementById("table").insertBefore(row, tableBody);
    
}