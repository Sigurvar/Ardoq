package codingTest;

import java.util.Collections;
import java.util.List;

public class Coding {
	
	public int getHighestProductFromList(List<Integer> ints) {
		if(ints.size()==0) {return 0;}
		Collections.sort(ints, Collections.reverseOrder());
		return ints.subList(0, (ints.size()<=3 ? ints.size() : 3)).stream().reduce(1, (a,b)->a*b);
	}
	
}
