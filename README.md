<h2>Simple md5 cracker</h2>

<h3>Used libraries:</h3>
 - https://github.com/dwyl/english-words for dictionary words.
Located in: components\helpers
 - https://github.com/noetix/Simple-ORM for orm
 
Install:
 - git clone git@github.com:requiem4/md5-simple-cracker.git
 - You would need to create database and set up access for it in 
 /config/config.php
 - Please use db.sql or db.sql.tar.gz as dump in config folder 
 
 <h4>Commands:</h4>
  php ./run.php 
      
   - Easy - The 4 user IDs who used 5 numbers as their passwords i.e. 12345
   - Medium - The 4 user IDs who used just 3 Uppercase characters and 1 number as their password i.e. ABC1
   - Medium - The 12 user IDs who used just lowercase dictionary words (Max 6 chars) as their passwords i.e. london
                       
  php ./run.php hard 
   - The 2 user IDs who used a 6 character passwords using a mix of Upper, Lowercase and numbers i.e AbC12z
    Note about number 4 - If you can figure this one out, it should also crack all four above. 

  <h4>Components</h4>
  Md5HashCracker - main class for decoding hashes. You can find all logic up there.
  Md5HashHelper - helper class for Md5HashCracker
