var createdRooms = {};
var answeredRooms = {};
var remainingRooms = {};
var roomResult = {};
var roomQuestions = {};
var roomUsers = {};


function populateDashboard()
{   
    document.getElementById("firstCreated").innerHTML= createdRooms[1].roomCode;
    document.getElementById("secondCreated").innerHTML = createdRooms[2].roomCode;
    document.getElementById("thirdCreated").innerHTML = createdRooms[3].roomCode;
    document.getElementById("fourthCreated").innerHTML = createdRooms[4].roomCode;
    document.getElementById("firstAnswered").innerHTML = answeredRooms[1].roomCode;
    document.getElementById("secondAnswered").innerHTML = answeredRooms[2].roomCode;
    document.getElementById("thirdAnswered").innerHTML = answeredRooms[3].roomCode;
    document.getElementById("fourthAnswered").innerHTML = answeredRooms[4].roomCode;
}

function getAnsweredModal(roomID)
{
    var payload = {};

    payload['userID'] = (localStorage.userID ? localStorage.userID : "");
    payload['sessionID'] = (localStorage.sessionID ? localStorage.sessionID : "");
    payload['roomID'] = roomID;
    var jsonPayload = JSON.stringify(payload);

    var url = "/API/AnsweredModal.php";

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
                

                if(jsonObject.error == ""){
                    window.alert('Successfully got Answered Modal')
                    var roomResult = jsonObject.roomResult;
                }else{
                    window.alert("Error getting Answered Modal")
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

function getCreatedModal(roomID)
{
    var payload = {};

    payload['userID'] = (localStorage.userID ? localStorage.userID : "");
    payload['sessionID'] = (localStorage.sessionID ? localStorage.sessionID : "");
    payload['roomID'] = roomID;
    var jsonPayload = JSON.stringify(payload);

    var url = "/API/RoomCreatedModal.php";

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
                

                if(jsonObject.error == ""){
                    window.alert('Successfully got Created Modal')
                    var roomQuestions = jsonObject.roomQuestions;
                    var roomUsers = jsonObject.roomUsers;
                }else{
                    window.alert("Error getting Created Modal")
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

function getDashboard()
{
    var payload = {};

    payload['userID'] = (localStorage.userID ? localStorage.userID : "");
    payload['sessionID'] = (localStorage.sessionID ? localStorage.sessionID : "");

    var jsonPayload = JSON.stringify(payload);

    var url = "/API/AdminPage.php";

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
                

                if (jsonObject.error == "") {
                    createdRooms = jsonObject.createdRooms;
                    answeredRooms = jsonObject.answeredRooms;
                    remainingRooms = jsonObject.remainingRooms;
                    
                } else {
                    window.alert("Error getting dashboard")
                }

            }
            else
            {
                return;
            }
        }
    }

    xhr.send(jsonPayload);
   // populateDashboard(createdRooms, answeredRooms, remainingRooms);
}



function populateModal(roomID){

    getCreatedModal(roomID);
    getAnsweredModal(roomID);
    var grade={};
    var numberOfQuestions={};
    for(var j = 0; j<roomUsers.numCorrect.length;j++)
    {
        grade[j] = roomUsers[x].numCorrect; 
    }
    var overallGrade = '100, 100, 95, 0, 35'.split(',');
    var counter = 1;
    var i;
    for(var x = 0; x<roomUsers.userID.length; x++)
    {
        numberOfQuestions[x] = roomUsers[x].userID;
    }
    var numberOfStudents = numberofIds.length

    //Reminder:
    //Need to make it so all accordion tabs are closed at the opening of the Modal "class = collapse hide"
    document.getElementById("accordionEx").innerHTML = '<div id="emptyDiv"><div class="card"><div class="card-header" role="tab" id="question' + s + '"><a data-toggle="collapse" data-parent="#accordionEx" href="#collapse' + s +  '" aria-expanded="true"aria-controls="collapse' + s +  '"><h5 class="mb-0">Overall Results' + " " +  '<i class="fa fa-angle-down rotate-icon"></i></h5></a></div><div id="collapse' + s +  '" class="collapse hide" role="tabpanel" aria-labelledby="heading' + s +  '"data-parent="#accordionEx"><div class="card-body"><div class="row"><div class="col-sm">Graphs</div><div class="col-sm d-flex justify-content-end"><div class="pre-scrollable"><ul style="height:200px; width: 165px;list-style-type: none" id="studentList"><li><div class="row"><div class="col-sm">Student</div><div class="col-sm">| Grade</div></li></ul></div></div></div></div></div></div></div>'

//Overall Results StudentList Population
for(i = 0; i < numberOfStudents; i++)
    {
        
     var newStudentList = document.createElement('LI');
        newStudentList.id = numberOfQuestions[s]; newStudentList.className = "car";
        newStudentList.innerHTML = numberOfQuestions[i] + "|" +  overallGrade[i];
        newStudentList.setAttribute("class", "col-sm list-group-item ")
        newStudentList.setAttribute("style", "list-style-type: none;")
        var studentList = document.getElementById("studentList");
        studentList.appendChild(newStudentList);
    }

for (var s in numberOfQuestions)
    {
    var newAccordion = document.createElement("div");
    newAccordion.setAttribute("class", "card");

    newAccordion.innerHTML = '<div class="card"><div class="card-header" role="tab" id="question' + s + '"><a data-toggle="collapse" data-parent="#accordionEx" href="#collapse' + s +  '" aria-expanded="true"aria-controls="collapse' + s +  '><h5 class="mb-0">Question' + " " + counter  +  '<i class="fa fa-angle-down rotate-icon"></i></h5></a></div><div id="collapse' + s +  '" class="collapse hide" role="tabpanel" aria-labelledby="heading' + s +  '"data-parent="#accordionEx"><div class="card-body"><div class="row"><div class="col-sm">Graphs</div><div class="col-sm d-flex justify-content-end"><div class="pre-scrollable"><ul style="height:200px; width: 165px;list-style-type: none" id="studentList' + s + '"><li><div class="row"><div class="col-sm">Student</div><div class="col-sm">| Grade</div></li></ul></div></div></div></div></div></div>'
    
    var emptyDiv = document.getElementById("emptyDiv");
    document.getElementById("accordionEx").insertBefore(newAccordion, emptyDiv);
    
    //populating studentLists
    for(i = 0; i < numberOfStudents; i++)
    {
        
     var newStudentList = document.createElement('LI');
        newStudentList.id = numberOfQuestions[s]; newStudentList.className = "car";
        newStudentList.innerHTML = numberOfQuestions[i] + "|" +  grade[i];
        newStudentList.setAttribute("class", "col-sm list-group-item ")
        newStudentList.setAttribute("style", "list-style-type: none;")
        var studentList = document.getElementById("studentList" + s);
        studentList.appendChild(newStudentList);
    }
    
counter++;

}

}

/*
     var students = '01234567,12345678,12345678,13245767,12453678'.split(',');
    var grade = ' 0,     1   , 2, 3, 4'.split(',');
    for (var s in students) {

        var newElement = document.createElement('LI');
        newElement.id = students[s]; newElement.className = "car";
        newElement.innerHTML = students[s] + "|" +  grade[s];
        newElement.setAttribute("class", "col-sm list-group-item ")
        newElement.setAttribute("style", "list-style-type: none;")
        var studentList = document.getElementById("studentList");
        studentList.appendChild(newElement);
    }
*/
/*
    for (var s in students) {
        var newElement = document.createElement('LI');
        newElement.id = students[s]; newElement.className = "car";
        newElement.innerHTML = students[s] + "|" +  grade[s];
        newElement.setAttribute("class", "col-sm list-group-item ")
        newElement.setAttribute("style", "list-style-type: none;")
        var studentList = document.getElementById("studentList");
        studentList.appendChild(newElement);
    }
    
    
}
function createAnswer(number, answerText)
{
    if (count[number] <= 16)
    {
        count[number]++;
        var answerField = document.createElement("div");
        answerField.setAttribute("class", "form-check");
        answerField.innerHTML = '<input class="form-check-input" type="radio" name="question' + number + 'Radios" id="question' + number + 'Answer' + count[number] +  '" value="' + count[number] + '"><label class="form-check-label" for="question' + number + 'Answer' + count[number] +  '">' + answerText + '</label>'
        var answerButton = document.getElementById("submitAnswer" + number);
        document.getElementById("question" + number).insertBefore(answerField, answerButton);
    }
}
function createQuestion(questionText, pollID, questionID)
{
    questionCount++;
    count.push(0);
    var questionButton = document.createElement("li");
    questionButton.innerHTML = '<a id = "newButton' + questionCount + '" class="btn btn-primary btn-block" onclick="toggleElement(' + questionCount + ')">Question ' + (questionCount+1) + '</a>';
    document.getElementById("questionsList").insertBefore(questionButton, blankListItem);
    var newQuestion = document.createElement("div");
    newQuestion.setAttribute("id", "question" + questionCount);
    newQuestion.innerHTML = '<div class = card><div class="card-body">' + questionText + '</div></div></br><a id = "submitAnswer' + questionCount + '" class="btn btn-primary" onclick="submitAnswer( ' + questionCount + ',&QUOT;' + pollID +  '&QUOT;,&QUOT;' + questionID + '&QUOT;)">Submit</a>'; //"submitAnswer( ' + questionCount + ',' + \' +  pollID +  \' + ',' + \' + questionID + \' + ')"
    document.getElementById("content").insertBefore(newQuestion, blankDiv);
    toggleElement(questionCount);
}
function toggleElement(number){
    previous = active;
    active = number;
    var previousQuestion = document.getElementById("question" + previous);
    previousQuestion.style.display = "none";
    var activeQuestion = document.getElementById("question" + active);
    activeQuestion.style.display = "";
}
function submitAnswer(x, pollID, questionID)
{
    var poll = {};
    window.alert("got here")
    //window.alert(document.getElementById('question' + x + 'Radios').checked)
    poll['userID'] = (localStorage.userID ? localStorage.userID : "");
    poll['sessionID'] = (localStorage.sessionID ? localStorage.sessionID : "");
    poll['roomID'] = pollID;
    poll['questionID'] = questionID;
    poll['choice'] = $('input[name=question' + x + 'Radios]:checked', '#question' + x).val();
    var jsonPayload = JSON.stringify(poll);
    //window.alert(jsonPayload);
    window.alert("got here again")
    var url = "/API/AnswerQuestion.php";
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
                    window.alert('Successfully Answered:' + x)
                }else{
                    window.alert("Error Answering Poll")
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
*/