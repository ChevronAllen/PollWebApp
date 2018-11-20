package com.example.said.pollingzone;

public class User {
    private static User instance;
    private String userid;
    private String sessionID;
    private String firstName;
    private String lastName;
    private Poll activePoll;

    public static User Instance(String userid, String session, String firstName, String lastName) {
        if(instance == null) {
            instance = new User(userid, session, firstName, lastName);
        }
        return instance;
    }

    public static User Instance() {
        return instance;
    }

    private User(String userid, String session, String firstName, String lastName) {
        setUserid(userid);
        setSessionID(session);
        setFirstName(firstName);
        setLastName(lastName);
        setActivePoll(null);
    }

    public static void appStartUp() {
        User.instance = null;
    }

    public String getUserid() {
        return userid;
    }

    public void setUserid(String userid) {
        this.userid = userid;
    }

    public String getSessionID() {
        return sessionID;
    }

    public void setSessionID(String sessionID) {
        this.sessionID = sessionID;
    }

    public String getFirstName() {
        return firstName;
    }

    public void setFirstName(String firstName) {
        this.firstName = firstName;
    }

    public String getLastName() {
        return lastName;
    }

    public void setLastName(String lastName) {
        this.lastName = lastName;
    }

    public Poll getActivePoll() {
        return activePoll;
    }

    public void setActivePoll(Poll activePoll) {
        this.activePoll = activePoll;
    }

    @Override
    public String toString() {
        return "User{" +
                "userid='" + userid + '\'' +
                ", sessionID='" + sessionID + '\'' +
                ", firstName='" + firstName + '\'' +
                ", lastName='" + lastName + '\'' +
                '}';
    }
}
