package codingTest;

import static org.junit.jupiter.api.Assertions.*;

import java.util.Arrays;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

class CodingTest {
	
	Coding c = new Coding();
	@BeforeEach
	void setUp() throws Exception {
	}

	@Test
	void testGetHighestProductFromList() {
		assertEquals(0,  c.getHighestProductFromList(Arrays.asList()));
		assertEquals(10*5*6,c.getHighestProductFromList(Arrays.asList(1, 10, 2, 6, 5, 3)));
		assertEquals(20*-20*-3, c.getHighestProductFromList(Arrays.asList(0, 5, -3, -1, -2, -20, 20, 10)));
	}

}