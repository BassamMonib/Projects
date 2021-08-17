package com.example.project

import android.content.ContextWrapper
import android.content.Intent
import android.content.SharedPreferences
import android.graphics.Bitmap
import android.media.MediaPlayer
import android.media.MediaRecorder
import android.net.Uri
import android.os.Build
import android.os.Bundle
import android.os.Environment
import android.provider.MediaStore
import android.util.Base64
import android.view.LayoutInflater
import android.view.View
import android.view.WindowManager
import android.widget.*
import android.widget.AdapterView.OnItemLongClickListener
import androidx.activity.result.contract.ActivityResultContracts
import androidx.annotation.RequiresApi
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.core.net.toUri
import com.bumptech.glide.Glide
import com.google.firebase.database.*
import com.google.firebase.database.ktx.database
import com.google.firebase.ktx.Firebase
import com.google.firebase.storage.ktx.storage
import com.google.zxing.*
import java.io.ByteArrayOutputStream
import java.io.File
import java.time.LocalDateTime
import java.util.*
import javax.crypto.Cipher
import javax.crypto.SecretKeyFactory
import javax.crypto.spec.IvParameterSpec
import javax.crypto.spec.PBEKeySpec
import javax.crypto.spec.SecretKeySpec
import kotlin.collections.ArrayList


class ChatScreen : AppCompatActivity() {

    lateinit var mr: MediaRecorder

    val data = ArrayList<Bitmap>()
    val dataKeys = ArrayList<String?>()

    companion object {
        val chatMap: MutableMap<String, ChildEventListener> = mutableMapOf()
    }

    var msgCount: Long = 0

    fun fillMessages(){
        val lst:ListView = findViewById(R.id.Messages)
        val customAdapter = QRAdapter(this, data)
        lst.adapter= customAdapter
    }

    object AESEncyption {

        const val secretKey = "tK5UTui+DPh8lIlBxya5XVsmeDCoUl6vHhdIESMB6sQ="
        const val salt = "QWlGNHNhMTJTQWZ2bGhpV3U=" // base64 decode => AiF4sa12SAfvlhiWu
        const val iv = "bVQzNFNhRkQ1Njc4UUFaWA==" // base64 decode => mT34SaFD5678QAZX

        fun encrypt(strToEncrypt: String) :  String? {

            val ivParameterSpec = IvParameterSpec(Base64.decode(iv, Base64.DEFAULT))
            val factory = SecretKeyFactory.getInstance("PBKDF2WithHmacSHA1")
            val spec =  PBEKeySpec(secretKey.toCharArray(), Base64.decode(salt, Base64.DEFAULT), 10000, 256)
            val tmp = factory.generateSecret(spec)
            val secretKey =  SecretKeySpec(tmp.encoded, "AES")
            val cipher = Cipher.getInstance("AES/CBC/PKCS7Padding")
            cipher.init(Cipher.ENCRYPT_MODE, secretKey, ivParameterSpec)
            return Base64.encodeToString(cipher.doFinal(strToEncrypt.toByteArray(Charsets.UTF_8)), Base64.DEFAULT)
        }

        fun decrypt(strToDecrypt : String) : String {

            val ivParameterSpec =  IvParameterSpec(Base64.decode(iv, Base64.DEFAULT))
            val factory = SecretKeyFactory.getInstance("PBKDF2WithHmacSHA1")
            val spec =  PBEKeySpec(secretKey.toCharArray(), Base64.decode(salt, Base64.DEFAULT), 10000, 256)
            val tmp = factory.generateSecret(spec);
            val secretKey =  SecretKeySpec(tmp.encoded, "AES")
            val cipher = Cipher.getInstance("AES/CBC/PKCS7Padding");
            cipher.init(Cipher.DECRYPT_MODE, secretKey, ivParameterSpec);
            return  String(cipher.doFinal(Base64.decode(strToDecrypt, Base64.DEFAULT)))
        }
    }

    fun msgListener(key:String, db:DatabaseReference, spin_:ProgressBar){
        chatMap[key] = db.child("Chats").child(key).addChildEventListener(object : ChildEventListener {

            override fun onChildAdded(dataSnapshot: DataSnapshot, prevChildKey: String?) {
                data.add(QR_ENC_DEC.QR_Encoder(dataSnapshot.value.toString()))
                dataKeys.add(dataSnapshot.key)
                fillMessages()
                if(msgCount > 0.toLong()) msgCount--
                if(msgCount == 0.toLong()) {
                    spin_.visibility = View.GONE
                    msgCount--
                }
            }

            override fun onChildChanged(dataSnapshot: DataSnapshot, prevChildKey: String?) {}
            override fun onChildRemoved(dataSnapshot: DataSnapshot) {}
            override fun onChildMoved(dataSnapshot: DataSnapshot, prevChildKey: String?) {}
            override fun onCancelled(databaseError: DatabaseError) {}
        })
    }

    @RequiresApi(Build.VERSION_CODES.O)
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        this.supportActionBar?.hide()
        setContentView(R.layout.activity_chat_screen)
        window.setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_PAN)   // For keyboard (if not then it will hide entering data area)

        val cw = ContextWrapper(applicationContext)
        val directory = cw.getExternalFilesDir(Environment.DIRECTORY_MUSIC)
        val file = File(directory, "recording" + ".mp3")

        val spin_:ProgressBar = findViewById(R.id.chatLoad)
        spin_.visibility = View.VISIBLE

        mr = MediaRecorder()

        val db = Firebase.database.reference
        val store = Firebase.storage.reference
        val sp: SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)

        var sendr = sp.getString("admin", "")
        sendr = sendr?.replace(".", "")

        var recvr = intent.getStringExtra("Recvr")
        recvr = recvr?.replace(".", "")

        lateinit var dbRef_:ValueEventListener
        dbRef_ = db.child("Chats").child(sendr+"_"+recvr).addValueEventListener(object: ValueEventListener{

            // NEEDED CHILDREN COUNT (SO USING IT, IT WILL GIVE COUNT AND INVOKE THE CHILD LISTENER)
            override fun onDataChange(snapshot: DataSnapshot) {
                msgCount = snapshot.childrenCount
                if(msgCount == 0.toLong()) spin_.visibility = View.GONE
                msgListener(sendr+"_"+recvr, db, spin_)

                // Closing Listening
                db.child("Chats").child(sendr+"_"+recvr).removeEventListener(dbRef_)
            }

            override fun onCancelled(error: DatabaseError) {}
        })

        val send:Button = findViewById(R.id.snd_btn)
        val txt:EditText = findViewById(R.id.msg)
        val _QRS:ListView = findViewById(R.id.Messages)

        send.setOnClickListener {

            val msg = txt.text.toString()
            if(msg != "") {
                val encryptedMSG = AESEncyption.encrypt(msg)
                db.child("Chats").child(sendr + "_" + recvr).push().setValue("SENDER: :$encryptedMSG")
                db.child("Chats").child(recvr + "_" + sendr).push().setValue("RECEVR: :$encryptedMSG")
                txt.setText("")
            }
        }

        _QRS.setOnItemClickListener { _, _, pos, _ ->

            val raw_QR_decrypt = QR_ENC_DEC.QR_Decoder(data[pos])

            if(raw_QR_decrypt.contains(": :")) {
                // Normal Text
                val TMP = AESEncyption.decrypt(raw_QR_decrypt.split(": :")[1])
                val msgBox = AlertDialog.Builder(this)
                msgBox.setTitle("SCANNED MESSAGE")
                msgBox.setMessage(TMP)
                msgBox.show()
            }
            else if(raw_QR_decrypt.contains(":__:")){

                // Download and show vid
                val TMP = AESEncyption.decrypt(raw_QR_decrypt.split(":__:")[1])
                Firebase.storage.reference.child(sendr + "_" + recvr).child(TMP!!)
                    .downloadUrl.addOnSuccessListener {

                        // VideoView display
                        val test:VideoView = findViewById(R.id.vid_box)
                        val mediaController = MediaController(this)
                        mediaController.setAnchorView(test)
                        test.setMediaController(mediaController)
                        test.setVideoURI(it)
                        test.start()
                    }
            }
            else if(raw_QR_decrypt.contains(":___:")){

                // Download and show audio
                val TMP = AESEncyption.decrypt(raw_QR_decrypt.split(":___:")[1])
                Firebase.storage.reference.child(sendr + "_" + recvr).child(TMP!!)
                    .downloadUrl.addOnSuccessListener {

                        // Audio dialog display
                        val tmp_med = MediaPlayer()
                        tmp_med.setDataSource(it.toString())
                        tmp_med.prepare()

                        val aud_box: AlertDialog = AlertDialog.Builder(this)
                            .setTitle("AUDIO CONTROLLER")
                            .setPositiveButton("PLAY", null) //Set to null. We override the onclick
                            .setNegativeButton("PAUSE", null)
                            .create()

                        aud_box.setOnShowListener {
                            aud_box.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener {
                                aud_box.getButton(AlertDialog.BUTTON_POSITIVE).isEnabled = false
                                aud_box.getButton(AlertDialog.BUTTON_NEGATIVE).isEnabled = true
                                tmp_med.start()
                                Toast.makeText(this, "Audio Playing...", Toast.LENGTH_LONG).show()
                            }
                            aud_box.getButton(AlertDialog.BUTTON_NEGATIVE).setOnClickListener {
                                aud_box.getButton(AlertDialog.BUTTON_POSITIVE).isEnabled = true
                                aud_box.getButton(AlertDialog.BUTTON_NEGATIVE).isEnabled = false
                                tmp_med.pause()
                                Toast.makeText(this, "Audio Paused...", Toast.LENGTH_LONG).show()
                            }
                        }

                        aud_box.show()
                        aud_box.getButton(AlertDialog.BUTTON_NEGATIVE).isEnabled = false
                    }
            }
            else{
                // Download and show image
                val TMP = AESEncyption.decrypt(raw_QR_decrypt.split(":_:")[1])
                Firebase.storage.reference.child(sendr + "_" + recvr).child(TMP!!)
                    .downloadUrl.addOnSuccessListener {

                        // ImageView in your Activity
                        val alertadd = AlertDialog.Builder(this@ChatScreen)
                        val factory = LayoutInflater.from(this@ChatScreen)
                        val view_: View = factory.inflate(R.layout.download_img_view, null)

                        // Download directly from StorageReference using Glide
                        Glide.with(this@ChatScreen)
                            .load(it)
                            .into(view_.findViewById(R.id.QR_Image))

                        alertadd.setTitle("SCANNED IMAGE")
                        alertadd.setView(view_)
                        alertadd.show()
                    }
            }
        }

        _QRS.onItemLongClickListener = OnItemLongClickListener { _, _, pos, _ ->

            val msgBox = AlertDialog.Builder(this)
            msgBox.setTitle("DELETING MESSAGE")
            msgBox.setMessage("Are you sure you want to delete this QR message ?")

            msgBox.setPositiveButton("Yes") { _, _ ->

                val spinDel:ProgressBar = findViewById(R.id.chatLoad)
                spinDel.visibility = View.VISIBLE

                // Removing in FireBase Storage
                val raw_QR_decrypt = QR_ENC_DEC.QR_Decoder(data[pos])
                if(raw_QR_decrypt.contains(":_:")){
                    val storeImgTimeKey = AESEncyption.decrypt(raw_QR_decrypt.split(":_:")[1])
                    store.child(sendr + "_" + recvr).child(storeImgTimeKey!!).delete()
                }
                else if(raw_QR_decrypt.contains(":__:")){
                    val storeImgTimeKey = AESEncyption.decrypt(raw_QR_decrypt.split(":__:")[1])
                    store.child(sendr + "_" + recvr).child(storeImgTimeKey!!).delete()
                }
                else if(raw_QR_decrypt.contains(":___:")){
                    val storeImgTimeKey = AESEncyption.decrypt(raw_QR_decrypt.split(":___:")[1])
                    store.child(sendr + "_" + recvr).child(storeImgTimeKey!!).delete()
                }

                val dbKey = dataKeys[pos]

                // Removing Locally
                data.removeAt(pos)
                dataKeys.removeAt(pos)

                // Removing in DB
                db.child("Chats").child(sendr + "_" + recvr).child(dbKey!!).removeValue().addOnCompleteListener {
                    fillMessages()
                    Toast.makeText(this, "Message Deleted Successfully !", Toast.LENGTH_LONG).show()
                    spinDel.visibility = View.GONE
                }
            }

            msgBox.setNegativeButton("No") { _, _ ->
                Toast.makeText(this, "Message Not Deleted !", Toast.LENGTH_LONG).show()
            }

            msgBox.show()
            true
        }

        fun isVid(uri: Uri?):Boolean {

            val checkStr = uri.toString().split('.')
            if(checkStr[checkStr.size - 1].contains("video"))
                return true
            return false
        }

        fun media_upload(uri: Uri?){

            // Listens to selected media
            val timeKey:String = LocalDateTime.now().toString()
            store.child(sendr + "_" + recvr).child(timeKey).putFile(uri!!)
            store.child(recvr + "_" + sendr).child(timeKey).putFile(uri!!)
            val encryptedMSG = AESEncyption.encrypt(timeKey)
            if(isVid(uri)){
                db.child("Chats").child(sendr + "_" + recvr).push().setValue("SENDER:__:$encryptedMSG")
                db.child("Chats").child(recvr + "_" + sendr).push().setValue("RECEVR:__:$encryptedMSG")
            }else {
                db.child("Chats").child(sendr + "_" + recvr).push().setValue("SENDER:_:$encryptedMSG")
                db.child("Chats").child(recvr + "_" + sendr).push().setValue("RECEVR:_:$encryptedMSG")
            }
        }

        val getContent = registerForActivityResult(ActivityResultContracts.GetContent()) { uri: Uri? ->
            media_upload(uri)
            txt.setText("")
        }

        val up_btn:Button = findViewById(R.id.up_btn)
        up_btn.setOnClickListener{

            // Opens Gallary
            getContent.launch("*/*")
        }

        val cls:Button = findViewById(R.id.cls)
        cls.setOnClickListener{

            if(data.isNotEmpty()) {
                val isClear = AlertDialog.Builder(this)
                isClear.setTitle("CLEARING ALL MESSAGES")
                isClear.setMessage("Are you sure you want to clear all messages ?")

                isClear.setPositiveButton("Yes") { _, _ ->

                    // Removing In Storage
                    for (value in data) {
                        val raw_QR_decrypt = QR_ENC_DEC.QR_Decoder(value)
                        if (raw_QR_decrypt.contains(":_:")) {
                            val storeImgTimeKey =
                                AESEncyption.decrypt(raw_QR_decrypt.split(":_:")[1])
                            store.child(sendr + "_" + recvr).child(storeImgTimeKey!!).delete()
                        }
                        else if (raw_QR_decrypt.contains(":__:")) {
                            val storeImgTimeKey =
                                AESEncyption.decrypt(raw_QR_decrypt.split(":__:")[1])
                            store.child(sendr + "_" + recvr).child(storeImgTimeKey!!).delete()
                        }
                        else if (raw_QR_decrypt.contains(":___:")) {
                            val storeImgTimeKey =
                                AESEncyption.decrypt(raw_QR_decrypt.split(":___:")[1])
                            store.child(sendr + "_" + recvr).child(storeImgTimeKey!!).delete()
                        }
                    }

                    // Removed Locally
                    data.clear()
                    dataKeys.clear()
                    // Removed In DB
                    db.child("Chats").child(sendr + "_" + recvr).removeValue()

                    // Refresh chat
                    fillMessages()
                }

                isClear.setNegativeButton("No") { _, _ ->
                    Toast.makeText(this, "Chat not cleared !", Toast.LENGTH_LONG).show()
                }

                isClear.show()
            }
        }

        val rec:Button = findViewById(R.id.rec_btn)
        rec.setOnClickListener{

            mr.setAudioSource(MediaRecorder.AudioSource.MIC)
            mr.setOutputFormat(MediaRecorder.OutputFormat.THREE_GPP)
            mr.setAudioEncoder(MediaRecorder.OutputFormat.AMR_NB)
            mr.setOutputFile(file)

            val deck: AlertDialog = AlertDialog.Builder(this).setTitle("RECORD AUDIO")
                .setPositiveButton("RECORD", null) //Set to null. We override the onclick
                .setNegativeButton("STOP", null)
                .setNeutralButton("PLAY", null)
                .create()

            deck.setOnShowListener {

                deck.getButton(AlertDialog.BUTTON_NEGATIVE).isEnabled = false
                deck.getButton(AlertDialog.BUTTON_NEUTRAL).isEnabled = false

                deck.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener {
                    deck.getButton(AlertDialog.BUTTON_POSITIVE).isEnabled = false
                    deck.getButton(AlertDialog.BUTTON_NEGATIVE).isEnabled = true
                    mr.prepare()
                    mr.start()
                    Toast.makeText(this, "Recording started...", Toast.LENGTH_LONG).show()
                }

                deck.getButton(AlertDialog.BUTTON_NEGATIVE).setOnClickListener {
                    deck.getButton(AlertDialog.BUTTON_POSITIVE).isEnabled = true
                    deck.getButton(AlertDialog.BUTTON_NEGATIVE).isEnabled = false
                    deck.getButton(AlertDialog.BUTTON_NEUTRAL).isEnabled = true
                    mr.stop()
                    mr.release()
                    Toast.makeText(this, "Recording Saved...", Toast.LENGTH_LONG).show()
                }

                deck.getButton(AlertDialog.BUTTON_NEUTRAL).setOnClickListener {

                    val mp = MediaPlayer()
                    mp.setDataSource(file.toString())
                    mp.prepare()

                    val cont: AlertDialog = AlertDialog.Builder(this)
                        .setTitle("AUDIO CONTROLLER")
                        .setPositiveButton("PLAY", null) //Set to null. We override the onclick
                        .setNegativeButton("PAUSE", null)
                        .setNeutralButton("SEND", null)
                        .create()

                    cont.setOnShowListener {
                        cont.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener {
                            cont.getButton(AlertDialog.BUTTON_NEGATIVE).isEnabled = true
                            cont.getButton(AlertDialog.BUTTON_POSITIVE).isEnabled = false
                            mp.start()
                            Toast.makeText(this, "Audio Playing...", Toast.LENGTH_LONG).show()
                        }
                        cont.getButton(AlertDialog.BUTTON_NEGATIVE).setOnClickListener {
                            cont.getButton(AlertDialog.BUTTON_NEGATIVE).isEnabled = false
                            cont.getButton(AlertDialog.BUTTON_POSITIVE).isEnabled = true
                            mp.pause()
                            Toast.makeText(this, "Audio Paused...", Toast.LENGTH_LONG).show()
                        }
                        cont.getButton(AlertDialog.BUTTON_NEUTRAL).setOnClickListener {
                            val timeKey:String = LocalDateTime.now().toString()

                            store.child(sendr + "_" + recvr).child(timeKey).putFile(file.toUri())
                            store.child(recvr + "_" + sendr).child(timeKey).putFile(file.toUri())

                            val encryptedMSG = AESEncyption.encrypt(timeKey)
                            db.child("Chats").child(sendr + "_" + recvr).push().setValue("SENDER:___:$encryptedMSG")
                            db.child("Chats").child(recvr + "_" + sendr).push().setValue("RECEVR:___:$encryptedMSG")
                            Toast.makeText(this, "Audio Sent...", Toast.LENGTH_LONG).show()

                            cont.dismiss()
                            deck.dismiss()
                        }
                    }

                    cont.show()
                    cont.getButton(AlertDialog.BUTTON_NEGATIVE).isEnabled = false
                }
            }
            deck.show()
        }

        // CAMERA MODULE

        val cam_btn:Button = findViewById(R.id.cam_btn)
        cam_btn.setOnClickListener {
            val takePictureIntent = Intent(MediaStore.ACTION_IMAGE_CAPTURE)
            startActivityForResult(takePictureIntent, 1)
        }

    } // on create

    @RequiresApi(Build.VERSION_CODES.O)
    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)

        if(resultCode == RESULT_OK && requestCode == 1) {
            val bmp: Bitmap? = data?.extras?.get("data") as Bitmap
            val stream = ByteArrayOutputStream()
            bmp!!.compress(Bitmap.CompressFormat.JPEG, 100, stream)
            val byteArray = stream.toByteArray()

            val db = Firebase.database.reference
            val store = Firebase.storage.reference
            val sp: SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)

            val sendr = sp.getString("admin", "")?.replace(".", "")
            val recvr = intent.getStringExtra("Recvr")?.replace(".", "")

            val timeKey: String = LocalDateTime.now().toString()
            store.child(sendr + "_" + recvr).child(timeKey).putBytes(byteArray)
            store.child(recvr + "_" + sendr).child(timeKey).putBytes(byteArray)
            val encryptedMSG = AESEncyption.encrypt(timeKey)

            db.child("Chats").child(sendr + "_" + recvr).push().setValue("SENDER:_:$encryptedMSG")
            db.child("Chats").child(recvr + "_" + sendr).push().setValue("RECEVR:_:$encryptedMSG")
        }
    }

    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<out String>,
        grantResults: IntArray
    ) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults)
    }
}