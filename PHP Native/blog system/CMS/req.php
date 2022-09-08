<?php 

/*
  // admin [all] , writer   [manage blog ]     writer [name ,email , password , image ,phone]
  // blogs (title,content,image,date,addedBy,category)


  # userRoles 
    id    title 



  #Users 
  id name email  password  image  phone     role_id 


# Category 
id    title 


  # Blog

  id  title  content  image  date  addedBy   category



  # Comments 
  id     username       comment-text       blog_id     





  users    blogs 
  1         m 
  1         1
============== 
  1     :   m 




  category        blog 
  1               m 
  1               1
====================== 
  1    :          m 
   



  blog      comments 
  1          m 
  1          1
===================== 
1        :   m    

 

*/


?>