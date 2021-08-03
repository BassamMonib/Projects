package com.example.project

import android.Manifest
import android.content.Intent
import android.content.SharedPreferences
import android.net.Uri
import android.os.Bundle
import android.view.View
import android.widget.*
import android.widget.AdapterView.OnItemLongClickListener
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.net.toUri
import com.google.firebase.database.ktx.database
import com.google.firebase.ktx.Firebase
import com.squareup.picasso.Picasso


class MainScreen : AppCompatActivity() {

    fun clearChatListeners(){
        val db = Firebase.database.reference
        for(r in ChatScreen.chatMap) {
            db.child("Chats").child(r.key).removeEventListener(r.value)
        }
        ChatScreen.chatMap.clear()
    }

    fun clearNotiListeners(){
        while (!ArchLifecycleApp.mutex.tryLock());
        if(ArchLifecycleApp.refs.size != 0){
            val db = Firebase.database.reference
            val frndsSP:SharedPreferences = getSharedPreferences("FRNDS", MODE_PRIVATE)
            val userSP:SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)
            var _user = userSP.getString("admin", "")
            _user = _user?.replace(".", "")
            var i = 0
            for(r in ArchLifecycleApp.refs){
                var tmp = frndsSP.getString("frnd$i", "")
                tmp = tmp?.replace(".", "")
                db.child("Chats").child(_user+"_"+tmp).removeEventListener(r)
                i++
            }
            ArchLifecycleApp.refs.clear()
            ArchLifecycleApp.notiMap.clear()
        }
        ArchLifecycleApp.mutex.unlock()
    }

    fun fillFriends(friends:ListView){

        // Checking locally for friends
        val sp:SharedPreferences = getSharedPreferences("FRNDS", MODE_PRIVATE)
        val counter = sp.getInt("counter", 0)

        // Inserting friends in array list to later set in list view
        val data = ArrayList<String>()
        for (indx in 0 until counter) {
            val tmp = sp.getString("frnd$indx", "")
            if(tmp != null) data.add(tmp)
        }

        // Setting friends in list view
        var myAdapter = ArrayAdapter<String>(this, android.R.layout.simple_expandable_list_item_1, data)
        friends.adapter= myAdapter
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        this.supportActionBar?.hide()
        setContentView(R.layout.activity_main_screen)

        ActivityCompat.requestPermissions(this ,
            arrayOf(Manifest.permission.READ_EXTERNAL_STORAGE,
                Manifest.permission.WRITE_EXTERNAL_STORAGE,
                Manifest.permission.RECORD_AUDIO,
                Manifest.permission.CAMERA),
            1122)

        // Profile setup
        val USER: SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)
        var ME = USER.getString("admin", "")
        val user_email:TextView = findViewById(R.id.user_email)
        user_email.text = ME

        val sp_pp: SharedPreferences = getSharedPreferences("Local_PP", MODE_PRIVATE)
        val user_pp = sp_pp.getString(ME, "")
        val img:ImageView = findViewById(R.id.user_pp)

        if(user_pp != "")
            Picasso.with(this).load(user_pp).placeholder(R.drawable.ic_launcher_foreground).into(img)

        img.setOnClickListener{
            startActivity(Intent(this, ProfiePic::class.java))
        }

        val add:Button = findViewById(R.id.add_friends)
        val friends:ListView = findViewById(R.id.friends)

        fillFriends(friends)

        add.setOnClickListener{
            startActivity(Intent(this, AddScreen::class.java))
        }

        friends.setOnItemClickListener { parent, view, position, id ->
            val sp:SharedPreferences = getSharedPreferences("FRNDS", MODE_PRIVATE)
            val move = Intent(this, ChatScreen::class.java)
            move.putExtra("Recvr", sp.getString("frnd$position", ""))
            startActivity(move)
        }

        friends.onItemLongClickListener = OnItemLongClickListener { _, _, pos, _ ->

            val sp:SharedPreferences = getSharedPreferences("FRNDS", MODE_PRIVATE)
            val tmp = sp.getString("frnd$pos", "")
            val msgBox = AlertDialog.Builder(this)
            msgBox.setTitle("DELETING FRIEND")
            msgBox.setMessage("Are you sure you want to unfriend $tmp ?")

            msgBox.setPositiveButton("Yes") { _, _ ->

                val spinDel: ProgressBar = findViewById(R.id.delLoad)
                spinDel.visibility = View.VISIBLE

                // Removing in DB
                val tmpsp: SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)
                var usr = tmpsp.getString("admin", "")
                usr = usr?.replace(".", "")
                val db = Firebase.database.reference
                val tmpKey = sp.getString("frndKey$pos", "")
                if(usr != null && tmpKey != null) {
                    db.child("Friends").child(usr).child(tmpKey).removeValue().addOnCompleteListener {
                        // Removing Locally
                        val counter = sp.getInt("counter", 0)
                        val edtr = sp.edit()
                        for(i in pos until counter-1) {
                            edtr.putString("frnd$i", sp.getString("frnd${i + 1}", ""))
                            edtr.putString("frndKey$i", sp.getString("frndKey${i + 1}", ""))
                            edtr.apply()
                            edtr.commit()
                        }
                        edtr.putInt("counter", counter-1)
                        edtr.apply()
                        edtr.commit()

                        fillFriends(friends)
                        Toast.makeText(this, "$tmp unfriended successfully !", Toast.LENGTH_LONG).show()
                        spinDel.visibility = View.GONE
                    }
                }
            }

            msgBox.setNegativeButton("No") { _, _ ->
                Toast.makeText(this, "Message Not Deleted !", Toast.LENGTH_LONG).show()
            }

            msgBox.show()
            true
        }

        val logout:Button = findViewById(R.id.log_out_btn)
        logout.setOnClickListener{

            val msgBox = AlertDialog.Builder(this)
            msgBox.setTitle("LOGGING OUT !")
            msgBox.setMessage("Are you sure you want to logout ?")

            msgBox.setPositiveButton("Yes") { _, _ ->

                clearNotiListeners()
                clearChatListeners()

                // Erasing user data
                val edtr1 = getSharedPreferences("User", MODE_PRIVATE).edit()
                val edtr2 = getSharedPreferences("FRNDS", MODE_PRIVATE).edit()

                edtr1.clear()
                edtr2.clear()

                edtr1.apply()
                edtr2.apply()

                edtr1.commit()
                edtr2.commit()

                val move = Intent(this, MainActivity::class.java)
                move.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
                startActivity(move)
                finish()
            }

            msgBox.setNegativeButton("No") { _, _ ->
                Toast.makeText(this, "User not logged out !", Toast.LENGTH_LONG).show()
            }

            msgBox.show()
        }
    } // onCreate

    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<out String>,
        grantResults: IntArray
    ) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults)
    }
}