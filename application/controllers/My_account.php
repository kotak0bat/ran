<?php

 	class My_account extends CI_Controller {
 	
 		public function __construct()
    {
        parent::__construct();
        // $this->load->helper('form_validation');     
    }
		
		public function index(){}
 		
 		
 		public function change_password()
 		{ 	
 			// $this->page_handler->member_page(); //For members only.
 			 /* Form Validation Set's */
 				$this->form_validation->set_rules('cur_pw','Current Password','required');
				$this->form_validation->set_rules('pin_code','Pin Code','required|max_length[27]|min_length[7]|trim');
 				$this->form_validation->set_rules('new_pw','New Password','required|max_length[20]|min_length[6]|trim');
 				$this->form_validation->set_rules('conf_pw','Confirm Password','required|matches[new_pw]');
 				
	 			if( $this->form_validation->run() != true )
	 			{ 
	 			  $this->load->view("vchangepassword"); //Load Form.
	 			} else { 
	 			  //Set a query to load our session information.
	 			  $sql = $this->db->select("*")->from("UserInfo")->where("UserName",$this->session->userdata("UserName"))->get();
	 			  
	 			  //Take each result set as a variable.
	 			  foreach($sql->result() as $my_info)
	 			  { 
	 			    //Database Variables.
	 			  	$db_password = $my_info->UserPass;
	 			  	$db_id	= $my_info->UserName;
					$pin_code = $my_info->UserPass2;
	 			  }
	 			  
	 			  //Check to see if form's password equals database password;
	 			  $pass = $this->input->post("cur_pw");
				  $pass2 = $this->input->post("pin_code");
				  if  (strtoupper(substr(md5($pass),0,19)) == $db_password && strtoupper(substr(md5($pass2),0,19)) == $pin_code ) { 
	 			    //Passwords were matched.
	 			  	//strtoupper(substr(md5($password),0,19));
					$fixed_pw = strtoupper(substr(md5($this->input->post("new_pw")),0,19)); //Prepare new password.
	 			  	//Update Password
	 			  	$update = $this->db->query("UPDATE UserInfo SET UserPass = '$fixed_pw' WHERE UserName ='$db_id'") or die(mysql_error());
	 			  	//Set notification to user redirecting to index page.
	 			  	$this->session->set_flashdata("notification","Password has been updated!");
	 			  	redirect(base_url(),"refresh");
	 			  } else { 
	 			    //Password didn't match db password
	 			  	echo "Password or PinCode is incorrect!";
	 			  	$this->load->view("vchangepassword");
	 			  }
	 			  
	 			}

 		
 		}
 	
 	}
	
?>