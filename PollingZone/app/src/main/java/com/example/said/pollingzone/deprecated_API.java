package com.example.said.pollingzone;

import android.os.AsyncTask;
import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Map;
import java.util.HashMap;
import java.math.BigInteger;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;


public class API implements AsyncResponse {
    enum activity {
        none, login, register, getPoll, poll, graph;
    }

    private static activity currentActivity = activity.none;

    public API(){ }

    protected void login(String email, String password) {
        Log.d(AppConsts.TAG, "Login Activity");
        API.currentActivity = activity.login;

        Map<String, String> postData = new HashMap<>();
        postData.put("userEmail", email);
        postData.put("password", getSHA(password));
        HttpPostAsyncTask task = new HttpPostAsyncTask(postData);
        task.delegate = this;
        task.execute(AppConsts.PHP_location + "/Login.php");
        return;
    }

    protected void register(String firstName, String lastName, String optionalName,
                               String userEmail, String password) {
        Log.d(AppConsts.TAG, "Register Activity");
        API.currentActivity = activity.register;
       //f
        Map<String, String> postData = new HashMap<>();
        postData.put("firstName", firstName);
        postData.put("lastName", lastName);
        postData.put("optionalName", optionalName);
        postData.put("userEmail", userEmail);
        postData.put("password", getSHA(password));
        HttpPostAsyncTask task = new HttpPostAsyncTask(postData);
        task.execute(AppConsts.PHP_location + "/Register.php");
        return;
    }

    protected void getPoll(String pollId) {
        Log.d(AppConsts.TAG, "GetPoll");
        API.currentActivity = activity.getPoll;

        Map<String, String> postData = new HashMap<>();
        String userid = "";
        String sessionID = "";
        if(User.Instance() != null) {
            userid = User.Instance().getUserid();
            sessionID = User.Instance().getSessionID();
        }
        postData.put("userID", userid);
        postData.put("sessionID", sessionID);
        postData.put("roomCode", pollId);
        HttpPostAsyncTask task = new HttpPostAsyncTask(postData);
        task.delegate = this;
        task.execute(AppConsts.PHP_location + "/GetPoll.php");
        return;
    }

    @Override
    public void processFinish(String output) {
        switch(API.currentActivity) {
            case none :
                break;
            case login :
                try {
                    JSONObject data = (JSONObject) new JSONTokener(output).nextValue();
                    String userid = data.getString("id");
                    if(userid.equals("0")) {
                        // TODO: Invalid user/password redirect to login w/ messege
                    }
                    else {
                        String firstName = data.getString("firstName");
                        String lastName = data.getString("lastName");
                        String sessionID = data.getString("sessionID");
                        User u = User.Instance(userid, sessionID, firstName,lastName);
                        Log.i(AppConsts.TAG, "User Created : " + u.toString());
                    }
                } catch (JSONException e) {

                }
                break;
            case register :
                try {
                    JSONObject data = (JSONObject) new JSONTokener(output).nextValue();
                    String error = data.getString("error");
                    if(error.equals("")) {
                        // SUCCESS
                        // TODO: redirect to login page
                    } else {
                        // TODO: error here, likely name user already exists. redirect back to create user page
                    }
                } catch (JSONException e) {

                }
                break;
            case poll :
                break;
            case graph :
                break;
            case getPoll:
                break;
        }
    }

    public static class HttpPostAsyncTask extends AsyncTask<String, Void, String> {
        // This is the JSON body of the post

        private AsyncResponse delegate = null;
        JSONObject postData;

        // This is a constructor that allows you to pass in the JSON body
        public HttpPostAsyncTask(Map<String, String> postData) {
            if (postData != null) {
                this.postData = new JSONObject(postData);
                Log.d(AppConsts.TAG, "JSON : " + this.postData.toString());
            }
        }

        // This is a function that we are overriding from AsyncTask. It takes Strings as parameters because that is what we defined for the parameters of our async task
        @Override
        protected String doInBackground(String... params) {
            String response = null;
            try {
                // This is getting the url from the string we passed in
                URL url = new URL(params[0]);

                // Create the urlConnection
                HttpURLConnection urlConnection = (HttpURLConnection) url.openConnection();


                urlConnection.setDoInput(true);
                urlConnection.setDoOutput(true);

                urlConnection.setRequestProperty("Content-Type", "application/json");

                urlConnection.setRequestMethod("POST");


                // OPTIONAL - Sets an authorization header
                urlConnection.setRequestProperty("Authorization", "someAuthString");

                // Send the post body
                if (this.postData != null) {
                    OutputStreamWriter writer = new OutputStreamWriter(urlConnection.getOutputStream());
                    writer.write(postData.toString());
                    writer.flush();
                }

                int statusCode = urlConnection.getResponseCode();

                if (statusCode == 200) {

                    InputStream inputStream = new BufferedInputStream(urlConnection.getInputStream());

                    response = convertInputStreamToString(inputStream);
                    Log.d(AppConsts.TAG, "response : " + response);
                } else {
                    // Status code is not 200
                    // Do something to handle the error
                }

            } catch (Exception e) {
                Log.d(AppConsts.TAG, e.getLocalizedMessage());
            }
            return response;
        }

        @Override
        protected void onPostExecute(String result) {
            delegate.processFinish(result);
        }

        private String convertInputStreamToString(InputStream inputStream) {
            BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(inputStream));
            StringBuilder sb = new StringBuilder();
            String line;
            try {
                while ((line = bufferedReader.readLine()) != null) {
                    sb.append(line);
                }
            } catch (IOException e) {
                e.printStackTrace();
            }
            return sb.toString();
        }
    }

    public static String getSHA(String input) {

        try {

            // Static getInstance method is called with hashing SHA
            MessageDigest md = MessageDigest.getInstance("SHA-256");

            // digest() method called
            // to calculate message digest of an input
            // and return array of byte
            byte[] messageDigest = md.digest(input.getBytes());

            // Convert byte array into signum representation
            BigInteger no = new BigInteger(1, messageDigest);

            // Convert message digest into hex value
            String hashtext = no.toString(16);

            while (hashtext.length() < 32) {
                hashtext = "0" + hashtext;
            }

            return hashtext;
        } catch (NoSuchAlgorithmException e) {
            System.out.println("Exception thrown"
                    + " for incorrect algorithm: " + e);

            return null;
        }
    }
}
