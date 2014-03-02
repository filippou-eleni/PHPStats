<?php
/**
 * PHP Statistics Library
 *
 * Copyright (C) 2011-2012 Michael Cordingley<Michael.Cordingley@gmail.com>
 * 
 * This library is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Library General Public License as published
 * by the Free Software Foundation; either version 3 of the License, or 
 * (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Library General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU Library General Public License
 * along with this library; if not, write to the Free Software Foundation, 
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 * 
 * LGPL Version 3
 *
 * @package PHPStats
 */
 
namespace PHPStats\ProbabilityDistribution;

/**
 * Hypergeometric class
 * 
 * Represents the hypergeometric distribution, which is the probability of
 * selecting a certain number of objects of interest from a population with
 * some larger number of objects of interest.
 * 
 * For more information, see: http://en.wikipedia.org/wiki/Hypergeometric_distribution
 */
class Hypergeometric extends ProbabilityDistribution {
	private $L;
	private $m;
	private $n;
	
	/**
	 * Constructor function
	 * 
	 * @param int $L The population size
	 * @param int $m The number of interesting elements in the population
	 * @param int $n The number of draws from the population.
	 */
	public function __construct($L = 1, $m = 1, $n = 1) {
		$this->L = $L;
		$this->m = $m;
		$this->n = $n;
	}
	
	/**
	 * Returns a random float between $minimum and $minimum plus $maximum
	 * 
	 * @return float The random variate.
	 */
	public function rvs() {
		return self::getRvs($this->L, $this->m, $this->n);
	}
	
	/**
	 * Returns the probability mass function
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function pmf($x) {
		return self::getPmf($x, $this->L, $this->m, $this->n);
	}
	
	/**
	 * Probability Distribution function
	 * 
	 * Alias for pmf
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function pdf($x) {
		return self::pmf($x);
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function cdf($x) {
		return self::getCdf($x, $this->L, $this->m, $this->n);
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function sf($x) {
		return self::getSf($x, $this->L, $this->m, $this->n);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives a cdf of $x
	 */
	public function ppf($x) {
		return self::getPpf($x, $this->L, $this->m, $this->n);
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives an sf of $x
	 */
	public function isf($x) {
		return self::getIsf($x, $this->L, $this->m, $this->n);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @return type array A dictionary containing the first four moments of the distribution
	 */
	public function stats($moments = 'mv') {
		return self::getStats($moments, $this->L, $this->m, $this->n);
	}
	
	/**
	 * Returns a random float between $minimum and $minimum plus $maximum
	 * 
	 * @param int $L The population size.
	 * @param int $m The number of interesting elements in the population.
	 * @param int $n The number of draws from the population
	 * @return float The random variate
	 * @static
	 */
	public static function getRvs($L = 1, $m = 1, $n = 1) {
		$successes = 0;
		for ($i = 0; $i < $n; $i++) {
			if (self::randFloat() <= $m/$L) {
				$m--;
				$successes++;
			}
			$L--;
		}
		return $successes;
	}
	
	/**
	 * Returns the probability mass function
	 * 
	 * @param float $x The test value
	 * @param int $L The population size
	 * @param int $m The number of interesting elements in the population
	 * @param int $n The number of draws from the population
	 * @return float The probability
	 * @static
	 */
	public static function getPmf($x, $L = 1, $m = 1, $n = 1) {
		$x = floor($x);
		$L = floor($L);
		$m = floor($m);
		$n = floor($n);
		
		if ($x > $L
		   || $x > $m
		   || $x > $n
		   || $x < 0
		   || $L < 1
		   || $m < 0
		   || $n < 0
		   || ) return 0;
		
		return (\PHPStats\Stats::combinations($m, $x)*\PHPStats\Stats::combinations($L - $m, $n - $x))/\PHPStats\Stats::combinations($L, $n);
	}
	
	/**
	 * Probability Distribution function
	 * 
	 * Alias for getPmf
	 * 
	 * @param float $x The test value
	 * @param int $L The population size
	 * @param int $m The number of interesting elements in the population
	 * @param int $n The number of draws from the population
	 * @return float The probability
	 * @static
	 */
	public static function getPdf($x, $L = 1, $m = 1, $n = 1) {
		return self::getPmf($x, $L, $m, $n);
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @param int $L The population size
	 * @param int $m The number of interesting elements in the population
	 * @param int $n The number of draws from the population
	 * @return float The probability
	 * @static
	 */
	public static function getCdf($x, $L = 1, $m = 1, $n = 1) {
		$x = floor($x);
		$L = floor($L);
		$m = floor($m);
		$n = floor($n);
		
		if ($L >= 1 && $m >= 0 && $n >= 0) {
			$sum = 0;
			for($i = 0; $i <= $x; $i++) $sum += (\PHPStats\Stats::combinations($m, $i)*\PHPStats\Stats::combinations($L - $m, $n - $i))/\PHPStats\Stats::combinations($L, $n);
			return $sum;
		}
		else return 0.0;
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @param int $L The population size
	 * @param int $m The number of interesting elements in the population
	 * @param int $n The number of draws from the population
	 * @return float The probability
	 * @static
	 */
	public static function getSf($x, $L = 1, $m = 1, $n = 1) {
		return 1.0 - self::getCdf($x, $L, $m, $n);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @param int $L The population size
	 * @param int $m The number of interesting elements in the population
	 * @param int $n The number of draws from the population
	 * @return float The value that gives a cdf of $x
	 * @static
	 */
	public static function getPpf($x, $L = 1, $m = 1, $n = 1) {
		$i = 0;
		$cdf = 0;
		
		while ($cdf < $x) {
			$cdf += self::getPmf($i, $L, $m, $n);
			$i++;
		}
		
		return $i - 1;
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @param int $L The population size
	 * @param int $m The number of interesting elements in the population
	 * @param int $n The number of draws from the population
	 * @return float The value that gives an sf of $x
	 * @static
	 */
	public static function getIsf($x, $L = 1, $m = 1, $n = 1) {
		return self::getPpf(1.0 - $x, $L, $m, $n);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @param int $L The population size
	 * @param int $m The number of interesting elements in the population
	 * @param int $n The number of draws from the population.
	 * @return type array A dictionary containing the first four moments of the distribution
	 * @static
	 */
	public static function getStats($moments = 'mv', $L = 1, $m = 1, $n = 1) {
		$return = array();
		
		if (strpos($moments, 'm') !== FALSE) $return['mean'] = ($n*$m)/$L;
		if (strpos($moments, 'v') !== FALSE) $return['variance'] = $n*($m/$L)*(($L - $m)/$L)*(($L - $n)/($L - 1));
		if (strpos($moments, 's') !== FALSE) $return['skew'] = (($L - 2*$m)*pow($L - 1, .5)*($L - 2*$n))/(pow($n*$m*($L - $m)*($L - $n), .5)*($L - 2));
		if (strpos($moments, 'k') !== FALSE) $return['kurtosis'] = (($L - 1)*pow($L, 2)*($L*($L + 1) - 6*$m*($L - $m) - 6*$n*($L - $n)) +6*$m*$n*($L - $m)*($L - $n)*(5*$L - 6))/($n*$m*($L - $m)*($L - $n)*($L - 2)*($L - 3));
		
		return $return;
	}
}
