package com.example.pesanpalgading20;

import android.os.Build;
import android.os.Bundle;

import androidx.annotation.RequiresApi;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentTransaction;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.Toolbar;

import com.example.pesanpalgading20.Makanan.BaksoBakar;
import com.example.pesanpalgading20.adapter.BaksoBakarAdapter;

import java.util.ArrayList;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link HomeMakananBaksoBakarFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class HomeMakananBaksoBakarFragment extends Fragment {

    ListView ListViewMenuBaksoBakar;
    Toolbar toolbarBaksoBakar;
    LinearLayout ContainerContentBaksoBakar;

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public HomeMakananBaksoBakarFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment HomeMakananBaksoBakar.
     */
    // TODO: Rename and change types and number of parameters
    public static HomeMakananBaksoBakarFragment newInstance(String param1, String param2) {
        HomeMakananBaksoBakarFragment fragment = new HomeMakananBaksoBakarFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View viewRoot = inflater.inflate(R.layout.fragment_home_makanan_bakso_bakar, container, false);
        ContainerContentBaksoBakar = viewRoot.findViewById(R.id.ContainerContentBaksoBakar);

        ArrayList<BaksoBakar> baksoBakar = new ArrayList<BaksoBakar>();
        baksoBakar.add (new BaksoBakar("Bakso Bakar Hore", 10000 ,R.drawable.ic_launcher_background));
        baksoBakar.add (new BaksoBakar("Bakso Bakar Yummie", 20000 ,R.drawable.ic_launcher_background));

        BaksoBakarAdapter baksoBakarAdapter = new BaksoBakarAdapter(getActivity(), baksoBakar);

        ListViewMenuBaksoBakar = viewRoot.findViewById(R.id.ListViewMenuBaksoBakar);
        ListViewMenuBaksoBakar.setAdapter(baksoBakarAdapter);

        toolbarBaksoBakar = viewRoot.findViewById(R.id.ToolbarBaksoBakar);
        toolbarBaksoBakar.setNavigationIcon(R.drawable.arrowbackicon);
        toolbarBaksoBakar.setNavigationOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                ContainerContentBaksoBakar.setVisibility(View.GONE);
                // Create new fragment and transaction
                Fragment FragmentMenuPilihan = new HomeMakananPilihanFragment();
                // consider using Java coding conventions (upper first char class names!!!)
                FragmentTransaction transaction = getFragmentManager().beginTransaction();
                // Replace whatever is in the fragment_container view with this fragment,
                // and add the transaction to the back stack
                transaction.replace(R.id.ContainerBaksoBakar, FragmentMenuPilihan);
                transaction.addToBackStack(null);
                // Commit the transaction
                transaction.commit();
            }
        });

        // Inflate the layout for this fragment
        return viewRoot;
    }
}