function addQuestion() {


    var questionField = document.createElement("div");
    questionField.setAttribute("class", "form-gourp");
    //questionField.setAttribute("type", "text");
    //questionField.setAttribute("style", "border: none");
    questionField.innerHTML = '<input type="text" class="form-control"><br>'

    document.getElementById("answersList").insertBefore(questionField, questionButton);
}
