package com.example.said.pollingzone;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

import java.util.HashMap;
import java.util.Map;

public class pollCode extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Log.d(AppConsts.TAG, "Anon Pollcode Activity");

        User.appStartUp();

        configureToCreateActivity();
        configureToPollActivity();
        configureToSignIn();
    }

    private void configureToCreateActivity()
    {
        Button goToCreateAccount = findViewById(R.id.goToCreateAccount);

        goToCreateAccount.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(pollCode.this, createAccountActivity.class));
            }
        });
    }

    private void configureToPollActivity()
    {
        setContentView(R.layout.activity_poll_code);

        Button goToPoll = findViewById(R.id.goToPoll);
        EditText pollingCode = findViewById(R.id.editPollCode);

        goToPoll.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                EditText pollingCode = findViewById(R.id.editPollCode);

                if(pollingCode.getText().toString().length() != 6)
                {
                    Toast.makeText(getApplicationContext(),
                            "POLLING CODE MUST BE 6 LETTERS",Toast.LENGTH_SHORT).show();

                    return;
                }

                Map<String, String> postData = new HashMap<>();
                User u = User.Instance();
                if(u != null) {
                    postData.put("userID", u.getUserid());
                    postData.put("sessionID", u.getSessionID());
                } else {
                    postData.put("userID", "");
                    postData.put("sessionID", "");
                }
                postData.put("roomCode", pollingCode.getText().toString());

                HttpPostAsyncTask task = new HttpPostAsyncTask(postData, new AsyncResponse() {
                    public void processFinish(String output) {
                        try  {
                            JSONObject data = (JSONObject) new JSONTokener(output).nextValue();
                            String error = data.getString("error");
                            if(error.equals("")) {
                                startActivity(new Intent(pollCode.this, GraphActivity.class));
                            } else {
                                // TODO: this means there is an error logging in, likely same email
                            }
                        } catch (JSONException e) {}
                    }
                });
                task.execute(AppConsts.PHP_location + "/GetPoll.php");
                finish();
            }
        });
    }

    private void configureToSignIn()
    {
        Button goToSignIn = findViewById(R.id.goToSignIn);

        goToSignIn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(pollCode.this, SignInActivity.class));
            }
        });
    }
}
