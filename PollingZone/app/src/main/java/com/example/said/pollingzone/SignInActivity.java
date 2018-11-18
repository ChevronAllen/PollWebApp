package com.example.said.pollingzone;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class SignInActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_in);

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


                //This is where we would create the JSONS and send it to the API
                startActivity(new Intent(SignInActivity.this, GraphActivity.class));
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

