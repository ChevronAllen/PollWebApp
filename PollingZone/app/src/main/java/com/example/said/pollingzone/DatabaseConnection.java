package com.example.said.pollingzone;

public final class DatabaseConnection {
    private static DatabaseConnection instance;

    private final String address = "107.180.25.129";
    private final String port = "3306";
    private final String database = "PollingZone";
    private final String apiLogin = "phpAPI";
    private final String apiPW = "Cop4331";

    static DatabaseConnection Instance() {
        if(instance == null)
            instance = new DatabaseConnection();
        return instance;
    }

    protected DatabaseConnection(){}
}
