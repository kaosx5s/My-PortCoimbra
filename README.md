# My~PortCoimbra
###Player Profile website for the MMO Granado Espada
Requirements:
- PHP 4.3+ (5.0+ preferred)
  - php-gd support required
- MYSQL 4.2+ (5.0+ preferred)

#####NOTICE: All code released as is, no support will be given. This project ran from ~08/2009 to 12/05/2012 and the code is beyond outdated. It exists here merely for archive purposes.

####Info:
The My~PortCoimbra project includes 5658 lines of code with 5333 of them being php. This project was by no means small but rather expanded quicker than my original expectations. Due to the progressive input from our beta testers I quickly transformed a simple signature generator into a massive "family management system" which also included a client side modification that would automatically update your signature every time you loaded your characters into the game. Despite programming in PHP for years prior to this project I found myself in new territory; attempting to update code with new functionality while also keeping old code intact. The nearly 6,000 lines of code are a testimate to my devotion to the project during its initial stages and first two years of activity.

#####NOTICE OF POSSIBLE EXPLOITS:
Due to the "release" of this code into the wild it is very likely that someone will stumble through it looking for exploits. Please remember that this code has not been touched in over two years so newer exploits or even some old ones may be possible. Deploying this code directly on another server is heavily discouraged; the main reason for this release is to help other developers continue the "signature generator" legacy for all Granado Espada players. I ask that if you do find an exploit to either report it to me or notify the webmaster of the site using this release. I am not responsible for loss of data due to a direct repurposing of the code released in this package.

#####NOTICE OF CODE REMOVAL:
Several functions (specifically the "remember me" and "password recovery key" generators) were removed from this release. This is to protect the integrity of our current database; although this database will no longer be accessable through the web I still believe that its better left unknown to outside eyes.

#####NOTICE OF INCOMPLETE CODE:
The mod panel (/mod/* directory) is incomplete, it was initially created so that "moderators" could help me with the amount of user bug requests I was recieving but development was stopped after traffic began to fall. Several of the files "work" but only if you know the ins and outs of the database which most people will not waste time with. I would suggest using these files only if you understand the possible loss of data and user / character corruption.
