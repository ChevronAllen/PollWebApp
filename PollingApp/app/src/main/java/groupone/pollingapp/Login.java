package groupone.pollingapp;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.*;

public class Login extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        Button loginCredentials = (Button) findViewById(R.id.SubmitCredentials);
        loginCredentials.setOnClickListener(
                new Button.OnClickListener() {
                    public void onClick(View v){
                        String userName = ((TextView) findViewById(R.id.input_email)).getText().toString();
                        String password = ((TextView) findViewById(R.id.input_password)).getText().toString();

                    }

                }
        );

        Button guestLogin = (Button) findViewById(R.id.GuestLogin);

        Button createAccount = (Button) findViewById(R.id.CreateAccount);


    }
}
