package com.example.project

import android.app.Application
import android.app.NotificationChannel
import android.app.NotificationManager
import android.app.PendingIntent
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.os.Build
import androidx.core.app.NotificationCompat
import androidx.core.app.NotificationManagerCompat
import androidx.lifecycle.Lifecycle
import androidx.lifecycle.LifecycleObserver
import androidx.lifecycle.OnLifecycleEvent
import androidx.lifecycle.ProcessLifecycleOwner
import com.google.firebase.database.DataSnapshot
import com.google.firebase.database.DatabaseError
import com.google.firebase.database.ValueEventListener
import com.google.firebase.database.ktx.database
import com.google.firebase.ktx.Firebase
import java.util.concurrent.locks.Lock
import java.util.concurrent.locks.ReentrantLock

class ArchLifecycleApp : Application(), LifecycleObserver {

    companion object {
        var refs: ArrayList<ValueEventListener> = ArrayList()
        var mutex: Lock = ReentrantLock(true)
        var notiID = 0
        val notiMap: MutableMap<String, Boolean> = mutableMapOf()
    }

    override fun onCreate() {
        super.onCreate()
        ProcessLifecycleOwner.get().lifecycle.addObserver(this)
    }

    @OnLifecycleEvent(Lifecycle.Event.ON_STOP)
    fun onAppBackgrounded() {

        val db = Firebase.database.reference

        val frndsSP:SharedPreferences = getSharedPreferences("FRNDS", MODE_PRIVATE)
        val userSP:SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)

        var _user = userSP.getString("admin", "")
        _user = _user?.replace(".", "")

        val frndsCOUNT = frndsSP.getInt("counter", 0)
        var tmpCOUNT = frndsCOUNT

        if(_user != "")
        for(i in 0 until frndsCOUNT) {

            var tmp = frndsSP.getString("frnd$i", "")
            tmp = tmp?.replace(".", "")

            if(tmp != null) notiMap[tmp] = true

            refs.add(db.child("Chats").child(_user+"_"+tmp)
                    .addValueEventListener(object : ValueEventListener {

                        override fun onDataChange(snapshot: DataSnapshot) {

                            while (!mutex.tryLock());

                            if(tmpCOUNT == 0){
                                if(notiMap[tmp] == true) {
                                    val x = snapshot.key?.split("_")?.get(1)

                                    val intent = Intent(baseContext, MainScreen::class.java)
                                    val pendingIntent: PendingIntent = PendingIntent.getActivity(baseContext, 0, intent, 0)

                                    var builder = NotificationCompat.Builder(baseContext, "1234")
                                        .setSmallIcon(R.drawable.common_google_signin_btn_icon_dark)
                                        .setContentTitle("NEW QR MESSAGE")
                                        .setContentText("You have received new QR message(s) from $x")
                                        .setContentIntent(pendingIntent)
                                        .setPriority(NotificationCompat.PRIORITY_DEFAULT)
                                        .setAutoCancel(true)

                                    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
                                        val importance = NotificationManager.IMPORTANCE_DEFAULT
                                        val channel = NotificationChannel("1234", ".....", importance).apply { description = "...." }

                                        val notificationManager: NotificationManager = getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
                                        notificationManager.createNotificationChannel(channel)

                                        with(NotificationManagerCompat.from(baseContext)) {
                                            notify(notiID, builder.build())
                                            notiID++
                                            notiMap[x!!] = false
                                        }
                                    }
                                }
                            }
                            else tmpCOUNT --

                            mutex.unlock()
                        }

                        override fun onCancelled(error: DatabaseError) {}
                    })
            )
        }


    }

    @OnLifecycleEvent(Lifecycle.Event.ON_START)
    fun onAppForegrounded() {

        while (!mutex.tryLock());

        if(refs.size != 0){

            val db = Firebase.database.reference

            val frndsSP:SharedPreferences = getSharedPreferences("FRNDS", MODE_PRIVATE)
            val userSP:SharedPreferences = getSharedPreferences("User", MODE_PRIVATE)

            var _user = userSP.getString("admin", "")
            _user = _user?.replace(".", "")

            var i = 0
            for(r in refs){
                var tmp = frndsSP.getString("frnd$i", "")
                tmp = tmp?.replace(".", "")
                db.child("Chats").child(_user+"_"+tmp).removeEventListener(r)
                i++
            }
            refs.clear()
            notiMap.clear()
        }

        mutex.unlock()
    }
}