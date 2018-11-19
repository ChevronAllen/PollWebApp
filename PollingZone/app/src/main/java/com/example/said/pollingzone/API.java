package com.example.said.pollingzone;

import android.util.Log;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.MalformedURLException;
import java.io.IOException;
import java.io.OutputStream;
import java.io.BufferedWriter;
import java.io.OutputStreamWriter;
import java.io.BufferedReader;
import java.io.InputStreamReader;

public class API {
    protected static boolean login(String username, String password) {

        try {
            String pollingZone = AppConsts.PHP_location + "login.php";
            URL url = new URL(pollingZone);
            HttpURLConnection urlConnection = (HttpURLConnection) url.openConnection();

            // prepare request
            urlConnection.setRequestMethod("POST");
            urlConnection.setDoInput(true);
            urlConnection.setDoOutput(true);
            urlConnection.setReadTimeout(10000);
            urlConnection.setConnectTimeout(15000);

            // upload request
            OutputStream outputStream = urlConnection.getOutputStream();
            BufferedWriter writer = new BufferedWriter(
                    new OutputStreamWriter(outputStream, "UTF-8"));
            writer.write("username" + "=" + username);
            writer.write("password" + "=" + password);
            writer.close();
            outputStream.close();

            // read response
            BufferedReader in = new BufferedReader(
                    new InputStreamReader(urlConnection.getInputStream()));

            String inputLine;
            StringBuffer response = new StringBuffer();
            while ((inputLine = in.readLine()) != null) { response.append(inputLine); }
            in.close();

            String result = response.toString();
            Log.i(AppConsts.TAG, result);
            
            // disconnect
            urlConnection.disconnect();
        } catch (MalformedURLException e) {
            Log.e(AppConsts.TAG, "Malformed URL Exception");
        } catch (IOException e) {
            Log.e(AppConsts.TAG, "IOException");
        }

        return true;
    }
}
