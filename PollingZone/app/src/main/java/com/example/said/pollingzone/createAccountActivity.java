package com.example.said.pollingzone;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class createAccountActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_create_account);

        configureBackToPollCode();
    }

    private void configureBackToPollCode()
    {
        Button backButton = findViewById(R.id.willSubmit);

        backButton.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view) {
                EditText password = findViewById(R.id.holdsPassword);
                EditText confirmPassword = findViewById(R.id.holdsConfirmedPassword);
                EditText email = findViewById(R.id.holdsEmail);
                EditText firstName = findViewById(R.id.holdsFirstName);
                EditText lastName = findViewById(R.id.holdsLastName);




                if(firstName.getText().toString().isEmpty() || lastName.getText().toString().isEmpty() || email.getText().toString().isEmpty() || confirmPassword.getText().toString().isEmpty() || password.getText().toString().isEmpty())
                {
                    Toast.makeText(getApplicationContext(),
                            "CAN NOT HAVE BLANK FIELDS",Toast.LENGTH_SHORT).show();

                    return;
                }
                else if(!isValidEmail(email.getText().toString()))
                {
                    Toast.makeText(getApplicationContext(),
                            "EMAIL IS INVALID",Toast.LENGTH_SHORT).show();

                    return;
                }
                else if(password.getText().toString().length() < 6 || password.getText().toString().length() > 20)
                {
                    Toast.makeText(getApplicationContext(),
                            "PASSWORD MUST BE OF LENGTH 6 THROUGH 20",Toast.LENGTH_SHORT).show();

                    return;
                }
                else if(!password.getText().toString().equals(confirmPassword.getText().toString()))
                {
                    Toast.makeText(getApplicationContext(),
                            "PASSWORDS DO NOT MATCH",Toast.LENGTH_SHORT).show();

                    return;
                }

                //This is where we would create the JSONS and send it to the API

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
