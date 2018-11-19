package com.example.said.pollingzone;
import java.util.ArrayList;

public class Poll {
    // Other necessary fields here i.e. poll title, id number ect
    private String roomId;
    private ArrayList<Question> questions;
    private int currentQuestion;

    public Poll() {
        this.questions = new ArrayList<>();
        this.currentQuestion = 0;
    }

    private void addQuestion(String qText, ArrayList<String> qAnswers) {
        this.questions.add(new Question(qText, qAnswers));
    }

    private void answerQuestion(int selectedAnswer) {
        this.questions.get(currentQuestion).answerQuestion(selectedAnswer);
        this.currentQuestion++;
    }

    private String[] getPossibleAnswers(){
        return this.questions.get(this.currentQuestion).getAnswers();
    }

    private String getText() {
        return this.questions.get(this.currentQuestion).getText();
    }

    private int[] getUserAnswers() {
        int[] answers = new int[this.questions.size()];
        for(int i = 0; i < this.questions.size(); i++) {
            answers[i] = this.questions.get(i).getUserAnswer();
        }
        return answers;
    }


    private class Question {
        private String text;
        private ArrayList<String> possibleAnswers;
        private int userAnswer;

        private Question() {
            this.text = new String();
            this.possibleAnswers = new ArrayList<>();
        }

        private Question(String qText, ArrayList<String> qAnswers) {
            this.text = qText;
            this.possibleAnswers = new ArrayList<>();
            this.possibleAnswers.addAll(qAnswers);
            this.userAnswer = -1;
        }

        private void answerQuestion(int answer) {
            this.userAnswer = answer;
        }

        private String[] getAnswers() {
            String[] answers = new String[this.possibleAnswers.size()];
            answers = this.possibleAnswers.toArray(answers);
            return answers;
        }

        private String getText() {
            return this.text;
        }

        private int getUserAnswer() {
            return this.userAnswer;
        }
    }
}
