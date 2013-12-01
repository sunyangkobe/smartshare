<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Car Data Model
 *
 * @author Europa Shang
 *
 */


class CarClass {
	
	const ECONOMY = 'Economy';
	const COMPACT = 'Compact';
	const INTERMEDIATE = 'Intermediate';
	const STANDARD = 'Standard';
	const FULL_SIZE = 'Full Size';
	const PREMIUM = 'Premium';
	const LUXURY = 'Luxury';
	const MINIVAN = 'Minivan';
	const INTERMEDIATE_SUV = 'Intermediate SUV';
	const LARGE_SUV = 'Large SUV';
	const PICKUP_TRUCK = 'Pickup Truck';
	const LARGE_PICKUP = 'Large Pickup';
	const CARGO_VAN = 'Cargo Van';
	
	public static function getValues() {
		
		return array("", CarClass::ECONOMY, CarClass::COMPACT, 
			CarClass::INTERMEDIATE, CarClass::STANDARD, CarClass::FULL_SIZE, 
			CarClass::PREMIUM, CarClass::LUXURY, CarClass::MINIVAN, 
			CarClass::INTERMEDIATE_SUV, CarClass::LARGE_SUV, CarClass::PICKUP_TRUCK, 
			CarClass::LARGE_PICKUP, CarClass::CARGO_VAN);
	}
	
	public static function getCarID($car_class) {
		return array_search($car_class, CarClass::getValues());
	}
	
	public static function getCar($car_id) {
		$cars = CarClass::getValues();
		return $cars[$car_id];
	}
}

?>