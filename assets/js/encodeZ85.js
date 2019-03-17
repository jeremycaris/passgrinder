// By Jeremy Caris, Mar 2019.
// https://passgrinder.com
// 
// Adapted from "encodeAscii85" function by Steve Hanov. Released to the public domain.
// Adapted to correctly encodes to Z85.
// 
// Verified via https://cryptii.com/pipes/z85-encoder
// Verified via https://github.com/msealand/z85.node/blob/master/index.js
// Z85 standard and correct character mapping: https://rfc.zeromq.org/spec:32/Z85/

var encodeZ85 = {
    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, chr4, chr, enc1, enc2, enc3, enc4, enc5;
        var i = 0;

        // Split Z85 Character Set into an array
        var alphabet = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.-:+=^!/*?&<>()[]{}@%$#".split("");

        while (i < input.length) {
            // Access past the end of the string is intentional.
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            chr4 = input.charCodeAt(i++);

            chr = ((chr1 << 24) | (chr2 << 16) | (chr3 << 8) | chr4) >>> 0;

            enc1 = (chr / (85 * 85 * 85 * 85) | 0) % 85;
            enc2 = (chr / (85 * 85 * 85) | 0) % 85 ;
            enc3 = (chr / (85 * 85) | 0 ) % 85;
            enc4 = (chr / 85 | 0) % 85;
            enc5 = chr % 85;

            output += alphabet[enc1] +
                alphabet[enc2];
            if (!isNaN(chr2)) {
                output += alphabet[enc3];
                if (!isNaN(chr3)) {
                    output += alphabet[enc4];
                    if (!isNaN(chr4)) {
                        output += alphabet[enc5];
                    }
                }
            }
        }
        return output;
    }
}
//var my_z85=encodeZ85.encode("Hello world!"); // nm=QNzY<mxA+]nf
//console.log('Hello world! = '+my_z85);