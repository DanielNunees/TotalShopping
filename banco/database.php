<?php

	//Ler Registros
	function DBRead($table, $params = null, $fields = '*'){
		$table = DB_PREFIX.'_'.$table;
		$params = ($params) ? " {$params}" : null;
		
		$query = "SELECT {$fields} FROM {$table}{$params}";
		$result = DBExecute($query);

		if(!mysqli_num_rows($result))
			return false;
		else{
			while ($res = mysqli_fetch_assoc($result)) {
				$data[] = $res;
			}
			return $data;			
		}	
	}

	function DBExecute($query){
		$link = DBConnect();
		$result = @mysqli_query($link,$query) or die(mysqli_error());
		
		DBClose($link);
		return $result;

	}