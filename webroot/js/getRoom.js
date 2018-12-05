var count = [];
var questionCount = -1;
var previous = 0;
var active = 0;

function createAnswer(number, answerText)
{
    if (count[number] <= 16)
    {
        count[number]++;

        var answerField = document.createElement("div");
        answerField.setAttribute("class", "form-check answers");
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

    newQuestion.innerHTML = '<div class = card><div class="card-body">' + questionText + '</div></div></br><a id = "submitAnswer' + questionCount + '" class="btn btn-primary" onclick="submitAnswer( ' + questionCount + ',&QUOT;' + pollID +  '&QUOT;,&QUOT;' + questionID + '&QUOT;)">Submit</a>'; 

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
