<?php
	// assume that the input is correctly written 
	// all requests to the server without an expression wants the history
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	$input = (json_decode(file_get_contents("php://input"), true));
	if ((isset($input['expression']))){
        calc($input['expression']);
    }
	else{
		getHistory();
	}
	
	function getHistory(){
		$list = array();
		$history = fopen("history.txt", "r");
		if ($history) {
			while (($line = fgets($history)) !== false) {
				$parts = preg_split("/[\s:,]+/",$line);
				$temp = array ("expression" => $parts[1], "result" => $parts[3]);
				array_push($list, $temp);
			}
			fclose($history);
		} else {
			echo "No previous history";
		}
		$return = json_encode($list);
		echo $return;
	}
	function setHistory($ex, $result){
		$history = fopen("history.txt", "a");
		fwrite($history,"expression:".$ex. ", result:".$result."\n");
		fclose($history);
	}

	function clean($ex){
		$prev =-1;
		$infront=null;
		for ($i=0;$i<strlen($ex);$i++){
			if($ex[$i]=="-"||$ex[$i]=="+"){$prev =$i+1;}
			if(($ex[$i]=="/"||$ex[$i]=="*")&&($ex[$i+1]=="-"||$ex[$i+1]=="+")){
				if ($prev==0){
					$ex = clean($ex[$i+1].substr($ex,0,$i+1).substr($ex,$i+2));
				}
				else{
					$infront = (((double)($ex[$prev-1]."1")*(double)($ex[$i+1]."1"))==1 ? "+":"-");
					$ex = clean(substr($ex,0,$prev-1).$infront.substr($ex,$prev,$i-$prev+1).substr($ex,$i+2));
				}
				break;
			}
		}
		return $ex;
	}
	
	function removeAllBrackets($term) {
		while(strpos($term,')')!=false){
			$firstRBracket = strpos($term,')');
			$matchingLBracket = strrpos(substr($term,0,$firstRBracket),'(');
			$simpleEx = substr($term, $matchingLBracket +1, $firstRBracket-$matchingLBracket -1);
			$term = substr($term, 0,$matchingLBracket) . calcTermNoBrackets($simpleEx) . substr($term, $firstRBracket+1);
		}
		return $term;
		
	}
	
	function calcTermNoBrackets($term){
		
		$parts = preg_split("/[\s+-]+/", $term);
		for($i=0;$i<count($parts);$i++){
			$sum =(double)$parts[$i];
			for($j=0;$j<strlen($parts[$i]);$j++){
				if($parts[$i][$j]=="*"){$sum=$sum*(double)substr($parts[$i],$j+1);}
				else if($parts[$i][$j]=="/"){$sum/=((double)substr($parts[$i],$j+1));}
			}
			$parts[$i] = $sum;
		}
		$result =$parts[0];
		$j=1;
		for($i=0;$i<strlen($term);$i++){
			if($term[$i]=="+"){$result=$result+$parts[$j];$j++;}
			elseif($term[$i]=="-"){$result=$result-$parts[$j];$j++;}
		}
		return $result;
	}
	
	function calc($ex){
		$ex = clean($ex);
		$termWithNoBrackets = removeAllBrackets($ex);
		$result = (string)round(calcTermNoBrackets($termWithNoBrackets),3);
		setHistory($ex, $result);
		echo json_encode(array("result"=> $result));
	}
?>