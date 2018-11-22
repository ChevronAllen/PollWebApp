package com.example.said.pollingzone;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
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

public class SignInActivity extends AppCompatActivity  {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_in);
        Log.d(AppConsts.TAG, "Login Activity");

        configureBackToPollCode();
    }



    private void configureBackToPollCode()
    {
        Button backButton = findViewById(R.id.willSubmit);

        backButton.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view) {
                EditText password = findViewById(R.id.holdsPassword);
                EditText email = findViewById(R.id.holdsEmail);


                if(email.getText().toString().isEmpty() || password.getText().toString().isEmpty())
                {
                    Toast.makeText(getApplicationContext(),
                            "CAN NOT HAVE BLANK FIELDS",Toast.LENGTH_SHORT).show();

                    return;
                }
                else if(!isValidEmail(email.getText().toString()))
                {
                    Toast.makeText(getApplicationContext(),
                            "INVALID CREDENTIALS",Toast.LENGTH_SHORT).show();

                    return;
                }
                else if(password.getText().toString().length() < 6 || password.getText().toString().length() > 20)
                {
                    Toast.makeText(getApplicationContext(),
                            "INVALID CREDENTIALS",Toast.LENGTH_SHORT).show();

                    return;
                }

                Map<String, String> postData = new HashMap<>();
                postData.put("userEmail", email.getText().toString());
                postData.put("password", AppConsts.getSHA(password.getText().toString()));
                HttpPostAsyncTask task = new HttpPostAsyncTask(postData, new AsyncResponse() {
                    public void processFinish(String output) {
                        try {
                            JSONObject data = (JSONObject) new JSONTokener(output).nextValue();
                            String userid = data.getString("id");
                            if(userid.equals("0")) {
                                startActivity(new Intent(SignInActivity.this, SignInActivity.class));
                            }
                            else {
                                String firstName = data.getString("firstName");
                                String lastName = data.getString("lastName");
                                String sessionID = data.getString("sessionID");
                                User u = User.Instance(userid, sessionID, firstName,lastName);
                                Log.i(AppConsts.TAG, "User Created : " + u.toString());
                                // TODO: this should redirect to the user dash/ poll code
                                startActivity(new Intent(SignInActivity.this, GraphActivity.class));
                            }
                        } catch (JSONException e) {}
                    }
                });
                task.execute(AppConsts.PHP_location + "/Login.php");
                finish();
            }
        });
    }

    private boolean isValidEmail(String Email)
    {
        char[] charArray = Email.toCharArray();

        for(int i = 0; i < charArray.length; i++)
        {
            if(charArray[i] == '@')
            {
                return true;
            }
        }

        return false;
    }

}
