package com.example.project

import android.content.Intent
import android.content.SharedPreferences
import android.os.Bundle
import android.view.View
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import com.google.firebase.auth.FirebaseAuth
import com.google.firebase.auth.ktx.auth
import com.google.firebase.database.DataSnapshot
import com.google.firebase.database.DatabaseError
import com.google.firebase.database.ValueEventListener
import com.google.firebase.database.ktx.database
import com.google.firebase.ktx.Firebase


class MainActivity : AppCompatActivity() {

    private lateinit var auth: FirebaseAuth
    private lateinit var dbref: ValueEventListener

    private fun addExistingFriends(_email:String){
        // Add his friends in arraylist
        val spin:ProgressBar = findViewById(R.id.spinny)
        spin.visibility = View.VISIBLE
        val dbRead = Firebase.database.reference
        val tmp = _email.replace(".", "")
        dbref = dbRead.child("Friends").child(tmp).addValueEventListener(object : ValueEventListener {
            override fun onDataChange(snapshot: DataSnapshot) {
                val sp:SharedPreferences = getSharedPreferences("FRNDS", MODE_PRIVATE)
                val edtr = sp.edit()
                var counter = sp.getInt("counter", 0)
                for (item in snapshot.children) {
                    edtr.putString("frnd$counter", item.value.toString())
                    counter++
                }
                edtr.putInt("counter", counter)
                edtr.apply()
                edtr.commit()

                // Closing Listening
                dbRead.child("Friends").child(tmp).removeEventListener(dbref)

                Toast.makeText(baseContext, "Successfully Logged In", Toast.LENGTH_SHORT).show()

                startActivity(Intent(baseContext, MainScreen::class.java))
                spin.visibility = View.INVISIBLE
                finish()
            }
            override fun onCancelled(error: DatabaseError) {}
        })
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        this.supportActionBar?.hide()
        setContentView(R.layout.activity_main)

        val sp:SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)
        val _email:EditText = findViewById(R.id._email)
        val _pass:EditText = findViewById(R.id._pass)
        val _log:Button = findViewById(R.id._log)
        val _sign:Button = findViewById(R.id._sign)

        val _user = sp.getString("admin", "")
        if(_user != "" && _user != null) {
            startActivity(Intent(this, MainScreen::class.java))
            finish()
        }

        _log.setOnClickListener{
            val e = _email.text.toString()
            val p = _pass.text.toString()
            if(e != "" && p != ""){
                auth = Firebase.auth
                auth.signInWithEmailAndPassword(e, p)
                    .addOnCompleteListener(this)
                    {   task ->
                        if (task.isSuccessful){
                            // User Logged In
                            val editor = sp.edit()
                            editor.putString("admin", e)
                            editor.apply()
                            editor.commit()
                            addExistingFriends(e)
                        }
                        else
                            Toast.makeText(MainActivity@ this, "Can't Login "+task.exception.toString(), Toast.LENGTH_SHORT).show()
                    }
            }
        }

        _sign.setOnClickListener{
            val e:String = _email.text.toString()
            val p = _pass.text.toString()
            if(e != "" && p != ""){
                auth = Firebase.auth
                auth.createUserWithEmailAndPassword(e, p)
                    .addOnCompleteListener(this)
                    {   task ->
                        if (task.isSuccessful) {
                            Toast.makeText(MainActivity@ this, "User Created", Toast.LENGTH_SHORT).show()
                            val dbWrite = Firebase.database.reference
                            dbWrite.child("People").push().setValue(e)
                        }
                        else
                            Toast.makeText(MainActivity@ this, "User Can't Be Created..."+task.exception.toString(), Toast.LENGTH_SHORT).show()
                    }
            }
        }

    }

}