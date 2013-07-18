<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index(){}
	
	public function upload(){
		
		$allowed_apps = $this->config->item('allowed_apps');
		
		$app_id = $this->input->post('app_id');
		if(!in_array($app_id, $allowed_apps)){
			die(json_encode(array('status' => 'error', 'msg' => 'Not allowed app')));
		}
		
	    $config['upload_path'] = 'uploads/';
	    $config['allowed_types'] = 'gif|jpg|png';
	    $config['max_size']  = 1024;
	    $config['encrypt_name'] = TRUE;
	     	 
	    $this->load->library('upload', $config);
	    if (!$this->upload->do_upload('file_contents')){
	    	$msg = $this->upload->display_errors('', '');
	        die(json_encode(array('status' => 'error', 'msg' => $msg)));
	    }    
		$data = $this->upload->data();
		
		if($hash = $this->input->post('hash')){
			$file_info = unserialize(base64_decode(urldecode($hash)));
			@unlink('uploads/'.$file_info['name']);
		}
		
		$result = array('name'=>$data['file_name'], 'content-type' => $data['file_type']);
		
		if($custom_data = $this->input->post('custom_data')){
			$result['custom_data'] = $custom_data;
		}
		
		$result = urlencode(base64_encode(serialize($result))); 
		die(json_encode(array('status' => 'success', 'file' => $result)));
	}
	
	public function show($hash){
		$file_info = @unserialize(base64_decode(urldecode($hash)));
		
		if(!$file_info){
			$this->not_found();
			die();
		}
		
		$file_path = 'uploads/'.$file_info['name'];
		
		if(!is_readable($file_path)){
			$this->not_found();
			die();			
		}
		
		header('Content-Type: '.$file_info['content-type']);
		readfile($file_path);
		die();
	}
	
	private function not_found(){
		?>
<svg 
     version="1.1" 
     xmlns="http://www.w3.org/2000/svg" 
     xmlns:xlink="http://www.w3.org/1999/xlink" 
     width="400px" height="400px" 
     viewBox="0 0 400 400" preserveAspectRatio="none"> 
   <g> 
<image width="400" height="400" xlink:href="data:image/png;base64, 
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
3a9KRZkAAAAASUVORK5CYII=
"/> 
   </g> 
</svg>

<?php		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */