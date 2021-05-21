package com.example.pesanpalgading20;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;

import android.Manifest;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Color;
import android.location.LocationManager;
import android.media.tv.TvContentRating;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.provider.Settings;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.KeyEvent;
import android.view.View;
import android.view.WindowManager;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.pesanpalgading20.model.LoginToMenu;
import com.example.pesanpalgading20.view.BottomNavbar;
import com.google.android.gms.location.LocationCallback;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationResult;
import com.google.android.gms.location.LocationServices;

import java.util.Random;

import static android.view.View.GONE;

public class LoginForm extends AppCompatActivity {

    private static final int REQUEST_CODE_LOCATION_PERMISSION = 1;
    LocationManager locationManager;
    android.widget.ProgressBar ProgressBar, ProgressBar1;
    Button BtnRefreshMeja,BtnMasuk,BtnMenu;
    EditText EdKodeMeja,EdNama;
    LinearLayout ContainerContentLogin;
    TextView TvLokasiMeja,TvNama;
    double lat1,lat2,lat3,lat4, lat5,
            long1, long2, long3, long4, long5,long6;
    Boolean poslat1, poslat2, poslat3, poslat4, poslat5,
            poslong1, poslong2, poslong3, poslong4, poslong5,poslong6;
    Boolean posk1confirm, posk2confirm, posk10confirm,
            posk3confirm, posk6confirm, posk9confirm, posk11confirm,
            posk4confirm, posk7confirm,posk12confirm;
    String unavailable;
    String ProgressBarVisible;

    Spinner SpinnerMeja;
    String[] NomorMeja = {"1","2","3","4","5/6","7","8/9","10","11","12"};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login_form);

        BtnRefreshMeja = (Button) findViewById(R.id.BtnRefreshMeja);
        EdKodeMeja = (EditText) findViewById(R.id.EdKodeMeja);
        EdNama = (EditText) findViewById(R.id.EdNama);
        SpinnerMeja = (Spinner) findViewById(R.id.SpinnerMeja);
        TvLokasiMeja = (TextView) findViewById(R.id.TvLokasiMeja);
        TvNama = (TextView) findViewById(R.id.TvNama);
        ProgressBar = (ProgressBar) findViewById(R.id.ProgressBar);
        ProgressBar1 = (ProgressBar) findViewById(R.id.ProgressBarMasuk);
        BtnMasuk = (Button) findViewById(R.id.BtnMasuk);
        BtnMenu = (Button) findViewById(R.id.BtnLihatMenu);

        ContainerContentLogin = findViewById(R.id.ContainerContentLogin);


        locationManager=(LocationManager) getSystemService(Context.LOCATION_SERVICE);

        SpinnerMeja.setVisibility(GONE);
        SpinnerMeja.setEnabled(false);
        SpinnerMeja.setClickable(false);
        EdKodeMeja.setText(getRandomString(6));

        //automatically detect lat long
        getLatLong();

        //Button LihatMenu is clicked
        BtnMenu.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                //Go To Menu Fragment
                FragmentTransaction fragmentTransaction = getSupportFragmentManager().beginTransaction();
                fragmentTransaction.replace(R.id.ContainerLogin,new MenuFragment()).commit();
                //Hide Main Container Login and be Replaced by MenuFragment
                ContainerContentLogin.setVisibility(GONE);

            }
        });

    }



    public void RandomizeCode(View view) {
        getLatLong();
    }

    public void getLatLong() {
        BtnRefreshMeja.setVisibility(GONE);
        BtnMasuk.setEnabled(false);
        BtnMenu.setEnabled(false);
        TvLokasiMeja.setVisibility(View.VISIBLE);
        SpinnerMeja.setVisibility(GONE);
        SpinnerMeja.setEnabled(false);
        SpinnerMeja.setClickable(false);
        EdKodeMeja.setText(getRandomString(6));
        //
        /////////////////Detect LangLong//////////////////////
        if (!locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER))
        {

            //Write Function To enable gps

            OnGPS();
        }
        else {

            if (ContextCompat.checkSelfPermission(
                    getApplicationContext(), Manifest.permission.ACCESS_FINE_LOCATION)
                    != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(
                        LoginForm.this,
                        new String[]{Manifest.permission.ACCESS_FINE_LOCATION},
                        REQUEST_CODE_LOCATION_PERMISSION
                );
            } else {
                ProgressBar.setVisibility(View.VISIBLE);
                getCurrentLocation();
                final Handler handler = new Handler(Looper.getMainLooper());
                handler.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        //Do something after 100ms
                        getCurrentLocation();
                    }
                }, 1000);
                handler.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        //Do something after 100ms
                        getCurrentLocation();
                    }
                }, 2000);
                handler.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        //Do something after 100ms
                        getCurrentLocation();
                    }
                }, 3000);
                ProgressBar.setVisibility(GONE);
            }
        }
    }


    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == REQUEST_CODE_LOCATION_PERMISSION && grantResults.length > 0) {
            if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                getCurrentLocation();
            }
        } else {
            Toast.makeText(this, "Permission denied", Toast.LENGTH_SHORT).show();
        }
    }

    private void OnGPS() {

        final AlertDialog.Builder builder= new AlertDialog.Builder(this);

        builder.setMessage("Enable GPS").setCancelable(false).setPositiveButton("YES", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                startActivity(new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS));
            }
        }).setNegativeButton("NO", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {

                dialog.cancel();
            }
        });
        final AlertDialog alertDialog=builder.create();
        alertDialog.show();
    }

    private void getCurrentLocation() {
        //////////////////////
        lat1 = -7.7283180;
        lat2 = -7.7283163;
        lat3 = -7.7283145;
        lat4 = -7.7283115;
        lat5 = -7.7283070;

        long1 = 110.4109377;
        long2 = 110.4109390;
        long3 = 110.4109416; //9420
        long4 = 110.4109444;
        long5 = 110.4109471;
        //////// long kursi 1 ////////////
        /// long kursi a = total long ///
        // yaitu long 5 /////////////////
        long6 = 110.4109471;


        BtnRefreshMeja.setVisibility(GONE);
        BtnRefreshMeja.setClickable(false);
        ProgressBar.setVisibility(View.VISIBLE);
        final LocationRequest locationRequest = new LocationRequest();
        locationRequest.setInterval(1000);
        locationRequest.setFastestInterval(1000);
        locationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);

        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            // TODO: Consider calling
            //    ActivityCompat#requestPermissions
            // here to request the missing permissions, and then overriding
            //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
            //                                          int[] grantResults)
            // to handle the case where the user grants the permission. See the documentation
            // for ActivityCompat#requestPermissions for more details.
            return;
        }


        LocationServices.getFusedLocationProviderClient(LoginForm.this)
                .requestLocationUpdates(locationRequest, new LocationCallback() {

                    @Override
                    public void onLocationResult(LocationResult locationResult) {
                        super.onLocationResult(locationResult);
                        LocationServices.getFusedLocationProviderClient(LoginForm.this)
                                .removeLocationUpdates(this);
                        if (locationResult != null && locationResult.getLocations().size() > 0) {
                            int latestLocationIndex = locationResult.getLocations().size() - 1;
                            double latitude =
                                    locationResult.getLocations().get(latestLocationIndex).getLatitude();
                            double longitude =
                                    locationResult.getLocations().get(latestLocationIndex).getLongitude();

                            poslong1 = false;
                            poslong2 = false;
                            poslong3 = false;
                            poslong4 = false;
                            poslong5 = false;
                            poslong6 = false;
                            poslat1 = false;
                            poslat2 = false;
                            poslat3 = false;
                            poslat4 = false;
                            poslat5 = false;
                            posk1confirm = false;
                            posk2confirm = false;
                            posk3confirm = false;
                            posk4confirm = false;
                            posk6confirm = false;
                            posk7confirm = false;
                            posk9confirm = false;
                            posk10confirm = false;
                            posk11confirm = false;
                            posk12confirm = false;
                            ///kursi 1 //

                            //// kursi 1 masih belom bisa kedeteksi ///
                            ///// kursi 1 2 3 4  ////
                            if (longitude > long1 && longitude < long2){
                                if (latitude > lat1 && latitude < lat2){
                                    posk1confirm = true;
                                    poslat1 = true;
                                }
                                else if (latitude > lat2 && latitude < lat3){
                                    posk2confirm = true;
                                    poslat3 = true;
                                }
                                else if (latitude > lat3 && latitude < lat4){
                                    posk3confirm = true;
                                    poslat4 = true;
                                }
                                else if (latitude > lat4 && latitude < lat5){
                                    posk4confirm = true;
                                    poslat5 = true;
                                }
                                else {
                                    posk2confirm = false;
                                    posk3confirm = false;
                                    posk4confirm = false;
                                    poslat3 = false;
                                    poslat4 = false;
                                    poslat5 = false;
                                }
                                //poslong2 = true;
                            }
                            ///// kursi 1 6 7 ////
                            else if (longitude > long2 && longitude < long3)  {

                                if (latitude > lat1 && latitude < lat2){
                                    posk1confirm = true;
                                    poslat1 = true;
                                }
                                else if (latitude > lat3 && latitude < lat4){
                                    posk6confirm = true;
                                    poslat4 = true;
                                }
                                else if (latitude > lat4 && latitude < lat5) {
                                    posk7confirm = true;
                                    poslat5 = true;
                                }
                                else {
                                    posk6confirm = false;
                                    posk7confirm = false;
                                    poslat4 = false;
                                    poslat5 = false;
                                }
                                poslong3 = true;
                            }
                            //// kursi 1 9 ///
                            else if (longitude > long3 && longitude < long4){

                                if (latitude > lat1 && latitude < lat2){
                                    posk1confirm = true;
                                    poslat1 = true;
                                }
                                else if (latitude > lat3 && latitude < lat4){
                                    posk9confirm = true;
                                    poslat4 = true;
                                }
                                else{
                                    posk9confirm = false;
                                    poslat4 = false;
                                }
                                poslong4 = true;
                            }
                            //// kursi 1 10 11 12 //
                            else if (longitude > long4 && longitude < long5){

                                if (latitude > lat1 && latitude < lat2){
                                    posk1confirm = true;
                                    poslat1 = true;
                                }
                                else if (latitude > lat2 && latitude < lat3){
                                    posk10confirm = true;
                                    poslat3 = true;
                                }
                                else if (latitude > lat3 && latitude < lat4){
                                    posk11confirm = true;
                                    poslat4 = true;
                                }
                                else if (latitude > lat4 && latitude < lat5){
                                    posk12confirm = true;
                                    poslat5 = true;
                                }
                                else {
                                    posk10confirm = false;
                                    posk11confirm = false;
                                    posk12confirm = false;
                                    poslat3 = false;
                                    poslat4 = false;
                                    poslat5 = false;
                                }
                                poslong5 = true;
                            }
                            /// kursi 1//
                            /*
                            else if (longitude > long2 && longitude < long4){
                                //
                                if (latitude > lat1 && latitude < lat2){
                                    posk1confirm = true;
                                    poslat1 = true;
                                }
                                else{
                                    posk1confirm = false;
                                    poslat1 = false;
                                }
                                poslong4 = true;
                            }
                            */
                            else {
                                poslong1 = false;
                                poslong2 = false;
                                poslong3 = false;
                                poslong4 = false;
                                poslong5 = false;
                                poslong6 = false;
                                poslat1 = false;
                                poslat2 = false;
                                poslat3 = false;
                                poslat4 = false;
                                poslat5 = false;
                                posk1confirm = false;
                                posk2confirm = false;
                                posk3confirm = false;
                                posk4confirm = false;
                                posk6confirm = false;
                                posk7confirm = false;
                                posk9confirm = false;
                                posk10confirm = false;
                                posk11confirm = false;
                                posk12confirm = false;
                            }

                            if (posk1confirm)
                            {
                                TvLokasiMeja.setText("1");
                            }
                            else if (posk2confirm)
                            {
                                TvLokasiMeja.setText("2");
                            }
                            else if (posk3confirm)
                            {
                                TvLokasiMeja.setText("3");
                            }
                            else if (posk4confirm)
                            {
                                TvLokasiMeja.setText("4");
                            }
                            else if (posk6confirm)
                            {
                                TvLokasiMeja.setText("6");
                            }
                            else if (posk7confirm)
                            {
                                TvLokasiMeja.setText("7");
                            }
                            else if (posk9confirm)
                            {
                                TvLokasiMeja.setText("9");
                            }
                            else if (posk10confirm)
                            {
                                TvLokasiMeja.setText("10");
                            }
                            else if (posk11confirm)
                            {
                                TvLokasiMeja.setText("11");
                            }
                            else if (posk12confirm)
                            {
                                TvLokasiMeja.setText("12");
                            }
                            else
                            {
                                TvLokasiMeja.setText("Deteksi Gagal");
                                TvLokasiMeja.setTextColor(Color.RED);
                            }



                            /////////////////////////////
                                /*
                                if (longitude > longb && longitude < longa){
                                    posalong = "true";
                                }
                                else if (longitude > longc && longitude < longd){
                                    posalong = "true";
                                }
                                else {
                                    posalong = "false";
                                }
                                */

                            ////////////////////////////


                        }
                        ProgressBar.setVisibility(GONE);
                        BtnMasuk.setEnabled(true);
                        BtnMenu.setEnabled(true);
                        BtnRefreshMeja.setClickable(true);
                        BtnRefreshMeja.setVisibility(View.VISIBLE);
                    }
                }, Looper.getMainLooper());
    }

    public static String getRandomString(int i){
        final String characters = "abcdefghijklmnopqrstuvwxyz1234567890";
        StringBuilder result = new StringBuilder();
        while (i > 0) {
            Random rand = new Random();
            result.append(characters.charAt(rand.nextInt(characters.length())));
            i--;
        }
        return result.toString();
    }

    public void EditSpinner(View view) {
        EdKodeMeja.setText(getRandomString(6));
        TvLokasiMeja.setVisibility(GONE);
        SpinnerMeja.setVisibility(View.VISIBLE);
        TvLokasiMeja.setText(" ");
        ArrayAdapter aa = new ArrayAdapter(this, android.R.layout.simple_spinner_item,NomorMeja);
        aa.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        SpinnerMeja.setEnabled(true);
        SpinnerMeja.setClickable(true);
        SpinnerMeja.setAdapter(aa);
    }

    public void masuk(View view) {

        //Check is Nama is empty
        if(EdNama.getText().toString().isEmpty()){
            EdNama.setError("Mohon masukkan nama terlebih dahulu");
            EdNama.requestFocus();
        }
        else {
            if(TvLokasiMeja.getText().toString() == "Deteksi Gagal"){
                Toast errorToast = Toast.makeText(LoginForm.this, "Deteksi meja gagal, silahkan refresh ulang atau edit secara manual", Toast.LENGTH_SHORT);
                errorToast.show();
            }
            else {
                Intent intent = new Intent(this, BottomNavbar.class);
                Bundle mbundle = new Bundle();
                String Nama = EdNama.getText().toString();
                String NoMejaAuto = TvLokasiMeja.getText().toString();
                String KodeMeja = EdKodeMeja.getText().toString();
                String NoMejaFinal;

                if (NoMejaAuto.equals(" ")){
                    NoMejaFinal = SpinnerMeja.getSelectedItem().toString();
                }
                else {
                    NoMejaFinal = NoMejaAuto;
                }

                intent.putExtra("nama", Nama);
                intent.putExtra("noMejaFinal", NoMejaFinal);
                intent.putExtra("kodeMeja", KodeMeja);
                startActivity(intent);
            }
        }
    }


    //Back is Pressed
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK) {
            exitByBackKey();
            //moveTaskToBack(false);
            return true;
        }
        return super.onKeyDown(keyCode, event);
    }

    protected void exitByBackKey() {

        AlertDialog alertbox = new AlertDialog.Builder(this)
                .setTitle("Keluar")
                .setMessage("Apakah anda ingin keluar ?")
                .setPositiveButton("Ya", new DialogInterface.OnClickListener() {

                    // do something when the button is clicked
                    public void onClick(DialogInterface arg0, int arg1) {
                        //Check if still inside Lihat Menu
                        if (ContainerContentLogin.getVisibility() == GONE){
                            Intent intent = new Intent(getBaseContext(), LoginForm.class);
                            startActivity(intent);
                        }
                        //if in the login form
                        else  {
                            //close application
                            finishAffinity();
                        }
                    }
                })
                .setNegativeButton("Tidak", new DialogInterface.OnClickListener() {

                    // do something when the button is clicked
                    public void onClick(DialogInterface arg0, int arg1) {
                        //do nothing
                    }
                })
                .show();

    }


}