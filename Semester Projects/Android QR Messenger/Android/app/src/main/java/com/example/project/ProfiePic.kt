package com.example.project

import android.content.ContextWrapper
import android.content.Intent
import android.content.SharedPreferences
import android.net.Uri
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.os.Environment
import android.widget.Button
import android.widget.ImageView
import androidx.activity.result.contract.ActivityResultContracts
import androidx.core.net.toUri
import com.squareup.picasso.Picasso
import java.io.File

class ProfiePic : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        this.supportActionBar?.hide()
        setContentView(R.layout.activity_profie_pic)

        val _sp: SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)
        var user_key = _sp.getString("admin", "")

        val sp: SharedPreferences = getSharedPreferences("Local_PP", MODE_PRIVATE)
        var ME_PP = sp.getString(user_key, "")
        val _image:ImageView = findViewById(R.id.pic)
        if(ME_PP != "")
            Picasso.with(this).load(ME_PP).placeholder(R.drawable.ic_launcher_foreground).into(_image)

        val getContent = registerForActivityResult(ActivityResultContracts.GetContent()) { uri: Uri? ->

            // Listens to gallry selected image
            val editor = sp.edit()
            editor.putString(user_key, uri.toString())
            editor.apply()
            editor.commit()

            ContextWrapper(applicationContext)
                .grantUriPermission("com.example.project", uri, Intent.FLAG_GRANT_READ_URI_PERMISSION)

            _image.setImageURI(uri)

            startActivity(Intent(this, MainScreen::class.java))
            finish()
        }

        val upload_btn:Button = findViewById(R.id.upload_btn)
        upload_btn.setOnClickListener{

            // Opens Gallary
            getContent.launch("image/*")
        }
    }
}