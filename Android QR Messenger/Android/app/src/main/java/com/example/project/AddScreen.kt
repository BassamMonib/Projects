package com.example.project

import android.content.Intent
import android.content.SharedPreferences
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.View
import android.view.Window
import android.view.WindowManager
import android.widget.Button
import android.widget.EditText
import android.widget.ProgressBar
import android.widget.Toast
import com.google.firebase.auth.ktx.auth
import com.google.firebase.database.DataSnapshot
import com.google.firebase.database.DatabaseError
import com.google.firebase.database.DatabaseReference
import com.google.firebase.database.ValueEventListener
import com.google.firebase.database.ktx.database
import com.google.firebase.ktx.Firebase

class AddScreen : AppCompatActivity() {

    private lateinit var _dbref:ValueEventListener

    fun info(addWait:ProgressBar, msg:String){

        Toast.makeText(baseContext, msg, Toast.LENGTH_LONG).show()
        addWait.visibility = View.GONE
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        this.supportActionBar?.hide()
        setContentView(R.layout.activity_add_screen)

        val spu:SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)
        val sp: SharedPreferences = getSharedPreferences("FRNDS", MODE_PRIVATE)

        val toAdd:EditText = findViewById(R.id._toAdd)
        val add_btn:Button = findViewById(R.id.add_btn)

        var db = Firebase.database.reference

        add_btn.setOnClickListener{

            val addWait:ProgressBar = findViewById(R.id.addWait)
            addWait.visibility = View.VISIBLE

            val friendTo = toAdd.text.toString()
            val me = spu.getString("admin", "")

            if(friendTo == me)
                info(addWait, "Can't add yourself as friend")

            else if(friendTo != "") {

                _dbref = db.child("People").addValueEventListener(object : ValueEventListener {

                    override fun onDataChange(snapshot: DataSnapshot) {

                        val counter = sp.getInt("counter", 0)
                        var flag: Boolean = true    // To check if any person found or not

                        for (item in snapshot.children) {
                            if (item.value == friendTo) { flag = false

                                // Already friend checker
                                var i = 0
                                while (i < counter) {
                                    if (sp.getString("frnd$i", "") == friendTo) break
                                    i++
                                }

                                if (i == counter) {

                                    // Add in RealTime Database
                                    val tmp = me?.replace(".", "")
                                    if (tmp != null) {
                                        // read the index key
                                        val valKey = db.child("Friends").child(tmp).push().key
                                        if (valKey != null) db.child("Friends").child(tmp).child(valKey).setValue(friendTo)
                                            .addOnCompleteListener{

                                                // Add Locally
                                                val edtr = sp.edit()
                                                edtr.putString("frnd$counter", friendTo)
                                                edtr.putString("frndKey$counter", valKey)
                                                edtr.putInt("counter", counter + 1)
                                                edtr.apply()
                                                edtr.commit()

                                                // Detach Listener
                                                db.child("People").removeEventListener(_dbref)

                                                startActivity(Intent(baseContext, MainScreen::class.java))
                                                info(addWait, "Friend Added")
                                                finish()
                                            }
                                    }

                                } else
                                    info(addWait, "Already Friend")

                                break
                            }
                        }

                        if (flag)
                            info(addWait, "No Person Found To Make Friend")
                    }

                    override fun onCancelled(error: DatabaseError) {}
                })
            }
        }
    }
}