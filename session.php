<?php

class session_class{
   private static $key;
   /***************************************
		function to start a session 
   ****************************************/
   public static function start($name=NULL){
       if(function_exists('session_status'))if (session_status() === PHP_SESSION_NONE)  session_start();
       else if(session_id() === '') session_start();
       else self::update_id();
       self::generate_key();
       if($name !=NULL)$_SESSION[$name];
   }
   /*************************************
		function create()
			input : associated array "session_name"=>"session_value"
			functionality: creates session;
   **************************************/
   public static function create($pairs){
       foreach($pairs as $name=>$val)$_SESSION[$name]=$val;
   }
   /************************************
		check if session exists!
   ************************************/
   public static function exists($name){return (isset($_SESSION[$name])&&$_SESSION[$name]!='');}
   public static function get($name){
       if(self::exists($name))return $_SESSION[$name];
       else return FALSE;
   }
   /************************************
		Update session idate
	************************************/
   public static function update_id(){
       self::generate_key();
       session_regenerate_id();
   }
   /************************************
		verify session secret key, to  prevent session hacking
		if failed halt execution and display error page!
   ************************************/
   public static function verify($name,$val){
       if(!isset($_SESSION['key'])&&$_SESSION['key']!=self::$key)
           common::error_page ('Internal Error');
       else return (isset($_SESSION[$name])&& $_SESSION[$name]==$val);
   }
   /**************************************
		generate secret key for sessions and save it in a session and in private member variable
   ***************************************/
   private static function generate_key(){
       $key='secure';
       $key .= (isset($_SERVER['HTTP_USER_AGENT']))?$_SERVER['HTTP_USER_AGENT']:"sdafsaferwfxcbghtrwegwresd";
       self::$key = md5($key);
       $_SESSION['key']= self::$key;
       return self::$key;
   }
   public static function kill($names){
       foreach($names as $name)if(isset($_SESSION[$name]))$_SESSION[$name]=NULL;
       
   }
   public static function destroy()
    {
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, '/');
        }
        session_destroy();
    }
}
