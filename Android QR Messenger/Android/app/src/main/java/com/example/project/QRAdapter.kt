package com.example.project

import android.content.Context
import android.graphics.Bitmap
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.BaseAdapter
import android.widget.ImageView

//Class MyAdapter
class QRAdapter(private val context: Context, private val arrayList: ArrayList<Bitmap>) : BaseAdapter() {

    private lateinit var QR_L: ImageView
    private lateinit var QR_R: ImageView

    override fun getCount(): Int {
        return arrayList.size
    }
    override fun getItem(position: Int): Any {
        return position
    }
    override fun getItemId(position: Int): Long {
        return position.toLong()
    }

    override fun getView(position: Int, convertView: View?, parent: ViewGroup): View? {

        var convertView = convertView
        convertView = LayoutInflater.from(context).inflate(R.layout.qr_s, parent, false)

        QR_L = convertView.findViewById(R.id.lmsg)
        QR_R = convertView.findViewById(R.id.rmsg)

        // DOING FOR CHECKING EITHER TO SET TO RIGHT OR LEFT
        lateinit var msgSplitted:List<String>
        val tmpQRTxt = QR_ENC_DEC.QR_Decoder(arrayList[position])
        if(tmpQRTxt.contains(": :")) msgSplitted = tmpQRTxt.split(": :")        // TEXT
        else if(tmpQRTxt.contains(":_:")) msgSplitted = tmpQRTxt.split(":_:")   // Image
        else if(tmpQRTxt.contains(":__:")) msgSplitted = tmpQRTxt.split(":__:")
        else msgSplitted = tmpQRTxt.split(":___:")

        if(msgSplitted[0] == "SENDER") QR_R.setImageBitmap(arrayList[position])
        else QR_L.setImageBitmap(arrayList[position])
        return convertView
    }
}