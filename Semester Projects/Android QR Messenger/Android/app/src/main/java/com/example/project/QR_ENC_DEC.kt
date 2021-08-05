package com.example.project

import android.graphics.Bitmap
import androidmads.library.qrgenearator.QRGContents
import androidmads.library.qrgenearator.QRGEncoder
import com.google.zxing.BinaryBitmap
import com.google.zxing.MultiFormatReader
import com.google.zxing.RGBLuminanceSource
import com.google.zxing.common.HybridBinarizer

class QR_ENC_DEC() {

    companion object {
        fun QR_Decoder(QR: Bitmap): String {

            val pixels = IntArray(QR.width * QR.height)
            QR.getPixels(pixels, 0, QR.width, 0, 0, QR.width, QR.height)

            val source = RGBLuminanceSource(QR.width, QR.height, pixels)
            val BinQR = BinaryBitmap(HybridBinarizer(source))

            return MultiFormatReader().decode(BinQR).text
        }

        fun QR_Encoder(txt: String): Bitmap {

            val qrgEncoder = QRGEncoder(txt, null, QRGContents.Type.TEXT, 600)
            return qrgEncoder.encodeAsBitmap()
        }
    }
}