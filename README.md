# PassGrinder

Passgrinder is a Wordpress plugin which currently inserts the PassGrinder form via the shortcode, `[passgrinder]`. To turn off the form title use, `[passgrinder showtitle="no"]`.

# The Problem

No security is absolute. Any security can be defeated with enough power, time, and resources. From mechanical locks to Fort Knox, no security is absolute.

In a nutshell, it is impossible to keep your password from being cracked if a hacker is determined to do so. The only effective measure that you can employ is to make the "cost" of cracking your password too high. The longer and more complex your password is, the more computing power, time, resources, and therefore money, it will take to crack it. On the other hand, if it is too long, it will "cost" the website too much to validate your password on login, so websites often limit how long your password can be.

Most website platforms today have methods of storing your password in a reasonably secure way. Pardon the technical jargon, but they store your password in a database online as a hash created with a salt. When you attempt to login, the website generates a salted password hash from the password you enter and compares it to the one stored in its database. If they match, you're in!

This is a good thing since it would be awful if they ever stored your plain password online! But, salted password hashes still have vulnerabilities. For instance, if someone can get that database, they gain access to the stored hashes for all users and the salt used to hash them. Now it's just a matter of trying to generate a bunch of hashes from attempted passwords until they find a hash that matches. Once they have a match, they know what that user's password is.

It is particularly easy to find a match by using dictionary attacks, lookup tables, reverse lookup tables, and rainbow tables. This is because there are many commonly used passwords and collections of passwords that have already been compromised before. If you create hashes from randomly combined words with numbers and symbols, you will eventually find matches. It's only a matter of time before matches are found, so the more time it takes to match your password hash, the more likely they will stop before they crack it.

If you want a more in-depth understanding of how this all works, we recommend [this excellent article](https://crackstation.net/hashing-security.htm).

The problem is, most of us can't remember extremely complex passwords, so most of us tend to use something we can remember. Worse yet, many of us use that same password on many different websites. Once that password has been compromised, it's very easy to guess that you may use it on Amazon with your stored payment method, on Facebook with access to more personal information, and even on your email which likely has evidence of what online banking service you use, allowing someone to recover your password from other sites and services, and even lock you out of your own email!

# The Solution

If you use a unique and complex password that is a "random" combination of numerals and symbols as well as uppercase and lowercase letters on every site you access, it would be far, far more unlikely to be compromised. That's where PassGrinder comes in. PassGrinder allows you to only need to remember your one simple, insecure, master password in order to generate a uniquely strong, complex password for each website you access.

PassGrinder will generate the same twenty-character "random" combination of numerals and symbols with uppercase and lowercase letters every time you feed it the same data. By random, we mean that there are no words in it, nothing sensible about it, nothing common or even readable, such as [leet speak](https://en.wikipedia.org/wiki/Leet). And since PassGrinder generates it on demand, you never have to save it in another place in plain text that may be potentially hacked or compromised.

Since the passwords generated by PassGrinders are so complex, the only attack that would really be effective is a brute-force attack. This means that they must generate every possible combination of numerals, symbols, uppercase letters, and lowercase letters to find a match. The quantity of combinations increases exponentially the longer the password becomes. At a length of twenty characters, the "cost" is very high, yet the password is not too long to be accepted by most websites.

# The Nuts & Bolts

PassGrinder uses the widely available MD5 hash generator. While MD5 is no longer considered a secure method of hashing passwords for storage, we are only creating a password, not storing it. PassGrinder takes your master password and generates an MD5 hash, outputting the raw binary data. Then, if you entered a unique phrase, it generates a raw binary data hash of that and appends it. Finally, if you select a variation, it generates the raw binary data hash for that variation and appends it also. PassGrinder then creates an MD5 hash of the full compiled hash and exports that as raw binary data as well.

This final hash ensures that PassGrinder always generates a twenty-character password by encoding it in the Anscii85 variation known as [Z85](https://rfc.zeromq.org/spec:32/Z85/). PassGrinder uses Z85 because it includes the entire English alphabet in uppercase and lowercase, all numerals, and all the symbols that are considered code-friendly, and coincidentally, password friendly as well. In other words, it doesn't include symbols that are often not usable in a password.

# The Method

PassGrinder has two key elements: (1) the form that collects your data, and (2) the function that "grinds" your data into a strong password. The form is written with PHP, which is a server-side computer language. This means that a server on the internet reads and interprets it to create the form you see in your browser. But the magic happens in your browser using a browser-side language called Javascript. In other words, once your browser has the form and the script it needs to know how to process the data in the form, it can do all the calculations required to generate your password itself. In other words, your password isn't sent over the web until you use it as your password on another site.

For full transparency, while [this website](https://passgrinder.com/) does no such thing, we should note that there is no stopping anyone from downloading and modifying the code or creating something similar and adding the ability to transmit and store your data and generated password. That is why we recommend you use [PassGrinder.com](https://passgrinder.com/) only or [download](https://passgrindercom.local/download/) and install the plugin on your own Wordpress site for your own use. You can also audit the [open-source code on Github](https://github.com/jeremycaris/passgrinder) to verify it for yourself.

# The Concept

PassGrinder was inspired by [KeyGrinder](http://keygrinder.com/), which was inspired by [PwdHash](http://crypto.stanford.edu/PwdHash/). Unfortunately, the passwords that KeyGrinder generates are simply too short to be secure enough to stand up against current common attack capabilities. So while PassGrinder isn't a new concept, it is a vastly improved modern implementation of strong password generation that is more appropriate for current standards.
