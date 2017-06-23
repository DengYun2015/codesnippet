/**
 * Created by dengyun on 2017/3/15.
 */
/**
 * RSA加解密
 * @type {{}}
 */
var crypto = require('crypto'),fs = require('fs'),helper=require('./helper');

function cutDataIntoArray(buffer, n){
    var length  = buffer.length;
    var data = [];
    for (var i = 0, l = length; i < l / n; i += 1) {
        const a = buffer.slice(n * i, n * (i + 1));
        data.push(new Buffer(a));
    }
    return data;
}

module.exports = {
    priKey: '',
    pubKey: '',
    initKeys: function (pubKey,priKey) {
        this.priKey = fs.readFileSync(priKey).toString();
        this.pubKey = fs.readFileSync(pubKey).toString();
    },
    publicEncrypt: function (data) {
        var step = 117;
        var encryptedList = [];
        var len = 0;
        for(var i=0,length=data.length;i<length;i+=step){
            var tmpData = data.substr(i,step);
            var bufferToEncrypt = new Buffer(tmpData);
            var encrypted = crypto.publicEncrypt(
                {"key" : this.pubKey, padding : crypto.constants.RSA_PKCS1_PADDING},
                bufferToEncrypt);
            len+=encrypted.length;
            encryptedList.push(encrypted);
        }
        return Buffer.concat(encryptedList,len).toString('base64');
    },
    privateDecrypt: function (data) {
        var bufferData = new Buffer(data, 'base64');
        var buffers = cutDataIntoArray(bufferData, 128);
/*        var decryptArr = buffers.map(
                buffer => crypto.privateDecrypt({
                key: rsaPriKey, padding: crypto.constants.RSA_PKCS1_PADDING
            }, buffer).toString()
        );*/
        var that = this;
        var decryptArr = buffers.map(
            function(buffer){
                return crypto.privateDecrypt({
                    key: that.priKey, padding: crypto.constants.RSA_PKCS1_PADDING
                }, buffer).toString()
            }
        );
        return decryptArr.join('');

    },
    sign: function (data) {
        var sign = crypto.createSign('RSA-SHA1');
        sign.update(new Buffer(data));
        return sign.sign(this.priKey, 'base64');
    },
    verify: function (data, sign) {
        var verify = crypto.createVerify('RSA-SHA1');
        sign.update(new Buffer(data));
        return verify.verify(this.pubKey, sign, 'base64');
    }
};
