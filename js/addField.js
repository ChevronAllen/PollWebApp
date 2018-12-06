var count = [0, 0];
var questionCount = 1;
var previous = 1;
var active = 1;
var maxQuestions = 0;

function addAnswer(number)
{
    if (count[number] <= 15)
    {
        count[number]++;
        var char = String.fromCharCode(count[number] + 64);

        var answerField = document.createElement("div");
        answerField.setAttribute("class", "form-group");
        answerField.innerHTML = '<input type="text" id="question' + number + 'answer' + count[number] + '" placeholder="' + char + '" padding-bottom="7px" class="form-control">'

        var answerButton = document.getElementById("addAnswerButton" + number);
        document.getElementById("answersList" + number).insertBefore(answerField, answerButton);

        var answerChar = document.createElement("option");
        answerChar.setAttribute("value", count[number]);
        answerChar.innerHTML = char;

        var blankOption = document.getElementById("blankOption" + number);
        document.getElementById("correctAnswerDropdown" + number).insertBefore(answerChar, blankOption);


    }
}

function addQuestion()
{
    if (questionCount < maxQuestions)
    {
        questionCount++;
        count.push(0);

        var questionButton = document.createElement("li");
        questionButton.innerHTML = '<a id = "newButton' + questionCount + '" class="btn btn-primary btn-block" onclick="toggleElement(' + questionCount + ')">Question ' + questionCount + '</a>';

        document.getElementById("questionsList").insertBefore(questionButton, blankListItem);

        var newQuestion = document.createElement("div");
        newQuestion.setAttribute("id", "question" + questionCount);

        newQuestion.innerHTML = '<div class="form-group"><br><textarea class="form-control" rows="5" id="questionField' + questionCount + '" placeholder="Enter Question Here"></textarea><form id = "answersList' + questionCount + '"><br><select class="input-large" id="correctAnswerDropdown' + questionCount + '"><option selected value="0">Select Correct Answer</option><span id="blankOption' + questionCount + '"></span></select><br><br><a id = "addAnswerButton' + questionCount + '" class="btn btn-primary" onclick="addAnswer(' + questionCount + ')">Add Answer</a></form></div>';

        document.getElementById("content").insertBefore(newQuestion, blankDiv);

        toggleElement(questionCount);
    }

}

function maxQuestions(x){
    maxQuestions = x;
}

function toggleElement(number){


    previous = active;
    active = number;


    var previousQuestion = document.getElementById("question" + previous);

    previousQuestion.style.display = "none";

    var activeQuestion = document.getElementById("question" + active);

    activeQuestion.style.display = "";

}

function submitQuestion()
{
    var poll = {};
    var startTime = new Date(document.getElementById("startTime").value);
    var endTime = new Date(document.getElementById("endTime").value);

    poll['userID'] = (localStorage.userID ? localStorage.userID : "");
    poll['sessionKey'] = (localStorage.sessionID ? localStorage.sessionID : "");
    poll['roomTitle'] = document.getElementById('title').value;
    poll['roomPublic'] = document.getElementById('publicOption').checked;
    poll['startTime'] = startTime.getTime();
    poll['expirationTime'] = endTime.getTime();
    poll['questions'] = []

    for(var i = 1; i <= questionCount; i++)
    {
        var tempPoll = {};

        tempPoll['correctResponse'] = document.getElementById('correctAnswerDropdown' + i).value;

        tempPoll['questionText'] = document.getElementById('questionField' + i).value;

        tempPoll['isLocked'] = "0";
        for(var j = 1; j <= 16; j++)
        {
            if((document.getElementById('question' + i + 'answer' + j)) != null)
            {
                tempPoll['choice' + j] = document.getElementById('question' + i + 'answer' + j).value;
            }else{
                tempPoll['choice' + j] = "";
            }

        }

        //window.alert(tempPoll);

        poll['questions'].push(tempPoll);

    }

    var jsonPayload = JSON.stringify(poll);

    //window.alert(jsonPayload);

    var url = "/API/CreateRoom.php";

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
                    window.alert('Poll Created! Room ID:' + jsonObject.roomCode)
                }else{
                    window.alert("Error Creating Poll")
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
