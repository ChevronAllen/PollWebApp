package com.example.said.pollingzone;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class pollCode extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_poll_code);

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


                //This is where we should go to the polling page
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
