<?php

/**
 * PlugintimpanyCartItems
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PlugintimpanyCartItem extends BasetimpanyCartItem
{
	/*public function __call($method, $args)
	{
		if ('get' == substr($method, 0, 3)) {
			$field = substr($method, 3);
			if (
			  sfInflector::underscore($field)
			  and isset($this->product_data[$field])
			) {
				return $this->product_data[$field];
			}
		}
		return parent::$method($args);
	}*/
	
  public function increaseCount($count)
  {
  	$this->count += $count;
  }
  
  public function getTaxPercent($region='de') {
  	return $this->getTaxClass()->getTaxPercent($region);
  }
  
  /**
   * get product property
   * 
   * @param string $key Attribute name
   * 
   * @see lib/model/doctrine/timpanyPlugin/base/BasetimpanyCartItem::getProductData()
   * @throws Doctrine_Record_UnknownPropertyException
   * 
   * @return mixed Product data
   */
  public function getProductAttribute($key)
  {
  	$product_data = $this->getProductData();
  	if (false == is_array($product_data)) {
  		$product_data = json_decode($product_data, true);
  	}
  	if (isset($product_data[$key])) {
  		return $product_data[$key];
  	}
  	throw new Doctrine_Record_UnknownPropertyException('cart item has no property named ' . $key);
  }
  
  /**
   * get tax class of this product
   * 
   * @return timpanyTaxClass
   */
  public function getTaxClass() {
  	return timpanyTaxClassTable::getInstance()->findOneById(
  	  $this->getProductAttribute('tax_class_id')
  	);
  }
  
  public function getNetSum()
  {
  	return $this->getProductAttribute('net_price') * (float) $this->getCount();
  }
  
  public function getGrossSum($region='de')
  {
    return $this->getGrossPrice($region) * (float) $this->getCount();
  }
  
  public function getGrossPrice($region='de')
  {
  	return round($this->getProductAttribute('net_price') * (1 + $this->getTaxClass()->getTaxRate($region)), 2);
  }
}