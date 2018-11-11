/*
options needed:
start time
end time
add to survey
require login to answer
format as code
save as draft
*/


var count = 0;

function addQuestion()
{
    if (count <= 7)
    {
        count++;

        var char = String.fromCharCode(count + 64);

        var answerField = document.createElement("div");
        answerField.setAttribute("class", "form-group");
        answerField.innerHTML = '<input type="text" id="answer' + count + '" placeholder="' + char + '" padding-bottom="7px" class="form-control">'

        document.getElementById("answersList").insertBefore(answerField, questionButton);
    }

}

function submitQuestion()
{
    var answers = {};

    answers['pollName'] = document.getElementById('title').value;
    answers['questionText'] = document.getElementById('questionField').value;
    answers['responseCount'] = count;
    answers['correctResponse'] = 2;
    answers['responses'] = [];

    for(var i = 1; i <= count; i++)
    {
        answers['responses'].push(document.getElementById('answer' + i).value);
        //answers.push(document.getElementById('answer' + i).value);
    }

    var jsonPayload = JSON.stringify(answers);

    var url = "../API/CreateAnonPoll.php";

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

                pollID = jsonObject.pollId;
                pollCode = jsonObject.pollCode;
                dateExpire = jsonObject.dateExpire;

            }
            else
            {
                return;
            }
        }
    }

    xhr.send(jsonPayload);

}
