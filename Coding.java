package codingTest;

import java.util.Collections;
import java.util.List;

public class Coding {
	
	public int getHighestProductFromList(List<Integer> ints) {
		if(ints.size()==0) {return 0;}
		else if(ints.size()<4) {return ints.stream().reduce(1, (a,b)->a*b);}
		
		Collections.sort(ints, Collections.reverseOrder());
		int posHigh = ints.get(1)* ints.get(2);
		int negHigh = ints.get(ints.size()-1)*ints.get(ints.size()-2);
		return ints.get(0) * (posHigh>negHigh ? posHigh : negHigh);
	}
	
}