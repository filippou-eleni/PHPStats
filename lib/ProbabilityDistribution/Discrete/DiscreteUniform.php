<?php
require_once('DiscreteDistribution.php');

class DiscreteUniform extends DiscreteDistribution {
	private $minimum;
	private $maximum;
	
	function __construct($minimum = 0, $maximum = 1) {
		$this->minimum = $minimum;
		$this->maximum = $maximum;
	}
	
	//These are wrapper functions that call the static implementations with what we saved.
	
	/**
		Returns a random float between $minimum and $minimum plus $maximum
		
		@return float The random variate.
	*/
	public function rvs() {
		return self::rvs($this->minimum, $this->maximum);
	}
	
	/**
		Returns the probability mass function
		
		@param float $x The test value
		@return float The probability
	*/
	public function pmf($x) {
		return self::pmf($x, $this->minimum, $this->maximum);
	}
	
	/**
		Returns the cumulative distribution function, the probability of getting the test value or something below it
		
		@param float $x The test value
		@return float The probability
	*/
	public function cdf($x) {
		return self::cdf($x, $this->minimum, $this->maximum);
	}
	
	/**
		Returns the survival function, the probability of getting the test value or something above it
		
		@param float $x The test value
		@return float The probability
	*/
	public function sf($x) {
		return self::sf($x, $this->minimum, $this->maximum);
	}
	
	/**
		Returns the percent-point function, the inverse of the cdf
		
		@param float $x The test value
		@return float The value that gives a cdf of $x
	*/
	public function ppf($x) {
		return self::ppf($x, $this->minimum, $this->maximum);
	}
	
	/**
		Returns the inverse survival function, the inverse of the sf
		
		@param float $x The test value
		@return float The value that gives an sf of $x
	*/
	public function isf($x) {
		return self::isf($x, $this->minimum, $this->maximum);
	}
	
	/**
		Returns the moments of the distribution
		
		@param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
		@return type array A dictionary containing the first four moments of the distribution
	*/
	public function stats($moments = 'mv') {
		return self::stats($moments, $this->minimum, $this->maximum);
	}
	
	//These represent the calculation engine of the class.
	
	/**
		Returns a random float between $minimum and $minimum plus $maximum
		
		@param float $minimum The minimum parameter.
		@param float $maximum The maximum parameter.
		@return float The random variate.
	*/
	static function rvs($minimum = 0, $maximum = 1) {
		return mt_rand($minimum, $maximum);
	}
	
	/**
		Returns the probability mass function
		
		@param float $x The test value
		@param float $minimum The minimum parameter
		@param float $maximum The maximum parameter
		@return float The probability
	*/
	static function pmf($x, $minimum = 0, $maximum = 1) {
		if ($x >= $minimum && $x <= $maximum) return 1.0/($maximum - $minimum);
		else return 0.0;
	}
	
	/**
		Returns the cumulative distribution function, the probability of getting the test value or something below it
		
		@param float $x The test value
		@param float $minimum The minimum parameter
		@param float $maximum The maximum parameter
		@return float The probability
	*/
	static function cdf($x, $minimum = 0, $maximum = 1) {
		if ($x >= $minimum && $x <= $maximum) return $x - $minimum / ($maximum - $minimum);
		elseif ($x > $maximum) return 1.0;
		else return 0.0;
	}
	
	/**
		Returns the survival function, the probability of getting the test value or something above it
		
		@param float $x The test value
		@param float $minimum The minimum parameter
		@param float $maximum The maximum parameter
		@return float The probability
	*/
	static function sf($x, $minimum = 0, $maximum = 1) {
		return 1.0 - self::cdf($x, $minimum, $maximum);
	}
	
	/**
		Returns the percent-point function, the inverse of the cdf
		
		@param float $x The test value
		@param float $minimum The minimum parameter
		@param float $maximum The maximum parameter
		@return float The value that gives a cdf of $x
	*/
	static function ppf($x, $minimum = 0, $maximum = 1) {
		return $minimum + ceiling($x*($maximum - $minimum));
	}
	
	/**
		Returns the inverse survival function, the inverse of the sf
		
		@param float $x The test value
		@param float $minimum The minimum parameter
		@param float $maximum The maximum parameter
		@return float The value that gives an sf of $x
	*/
	static function isf($x, $minimum = 0, $maximum = 1) {
		return self::ppf(1.0 - $x, $minimum, $maximum);
	}
	
	/**
		Returns the moments of the distribution
		
		@param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
		@param float $minimum The minimum parameter. Default 0
		@param float $maximum The maximum parameter. Default 1
		@return type array A dictionary containing the first four moments of the distribution
	*/
	static function stats($moments = 'mv', $minimum = 0, $maximum = 1) {
		$moments = array();
		
		if (strpos($moments, 'm') !== FALSE) $moments['mean'] = 0.5*($maximum + $minimum);
		if (strpos($moments, 'v') !== FALSE) $moments['variance'] = (1.0/12)*pow(($maximum - $minimum + 1), 2);
		if (strpos($moments, 's') !== FALSE) $moments['skew'] = 0;
		if (strpos($moments, 'k') !== FALSE) $moments['kurtosis'] = -(6.0*(pow(($maximum - $minimum + 1), 2)+ 1 ))/(5.0*(pow(($maximum - $minimum + 1), 2) - 1));
		
		return $moments;
	}
}
?>