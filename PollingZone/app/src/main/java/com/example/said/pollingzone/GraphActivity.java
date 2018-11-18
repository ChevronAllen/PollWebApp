package com.example.said.pollingzone;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;

import com.github.mikephil.charting.charts.BarChart;
import com.github.mikephil.charting.data.BarData;
import com.github.mikephil.charting.data.BarDataSet;
import com.github.mikephil.charting.data.BarEntry;


import java.util.ArrayList;

public class GraphActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_graph);

        setUpGraph();
    }

    private void setUpGraph()
    {
        BarChart barChart = findViewById(R.id.holdsBarChart);

        ArrayList<BarEntry> barEntries = new ArrayList<>();
        barEntries.add(new BarEntry(44f,0));
        barEntries.add(new BarEntry(56f,1));
        barEntries.add(new BarEntry(77f,2));
        barEntries.add(new BarEntry(32f,3));
        barEntries.add(new BarEntry(66f,4));
        barEntries.add(new BarEntry(25f,5));
        BarDataSet barDataSet = new BarDataSet(barEntries, "Dates");

        ArrayList<String> theDates = new ArrayList<>();
        theDates.add("April");
        theDates.add("May");
        theDates.add("June");
        theDates.add("July");
        theDates.add("August");
        theDates.add("September");

        BarData theData = new BarData(theDates, barDataSet);
        barChart.setData(theData);
        barChart.setTouchEnabled(false);
        //barChart.setDragEnabled(true);
        //barChart.setScaleEnabled(true);
        barChart.setPinchZoom(true);

    }
}
