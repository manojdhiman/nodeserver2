public function notifyNode($type,$user_id,$msg,$to_users,$guid=null,$convoguid=null,$data=null) 
	{
		
		$json = json_encode(array('type' => $type, 'user_id' => $user_id, 'message' => $msg,'to_users' => $to_users,'guid'=>$guid,'convoid'=>$convoguid,'data'=>$data));
		
		$url = Yii::app()->params['socket_server']."/push";
		
		$ch = curl_init($url);                                     
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
												   'Content-Length: ' . strlen($json)));      

		$data = curl_exec($ch);        
		curl_close($ch);       
	}
