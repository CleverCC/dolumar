<?php
/**
 *  Dolumar, browser based strategy game
 *  Copyright (C) 2009 Thijs Van der Schaeghe
 *  CatLab Interactive bvba, Gent, Belgium
 *  http://www.catlab.eu/
 *  http://www.dolumar.com/
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along
 *  with this program; if not, write to the Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

class Dolumar_Effects_Battle_Fear extends Dolumar_Effects_Battle implements Dolumar_Players_iBoost
{
	protected $iLevel;

	public function __construct ($iLevel = 1)
	{
		$this->iLevel = $iLevel;
	}
	
	protected function getBonusFromLevel ()
	{
		//return 10 + $this->getLevel () * 15;
		
		switch ($this->getLevel ())
		{
			case 1:
			default:
				return 1;
			break;
			
			case 2:
				return 2;
			break;
			
			case 3:
				return 3;
			break;
			
			case 4:
				return 4;
			break;
			
			case 5:
				return 5;
			break;
		}
	}
	
	public function getDescription ($data = array ())
	{
		return parent::getDescription
		(
			array
			(
				'penalty' => $this->getBonusFromLevel ()
			)
		);
	}
	
	/*
		Meteor rain
	*/
	public function doAction ($fight, &$attackers, &$defenders)
	{
		foreach ($defenders as $v)
		{
			$v->addEffect ($this);
		}
		
		return array ($this->getBonusFromLevel ());
		
		//return $this->doRandomDamage ($defenders, $damage, 'defMag', $this->iLevel);
	}
	
	public function getBattleLog ($report, $unit, $probability, $data, $html = true)
	{
		$text = Neuron_Core_Text::__getInstance ();
		return Neuron_Core_Tools::putIntoText
		(
			$text->get ('onSuccess', $this->getClassName (), 'effects'),
			array
			(
				'penalty' => $data[0],
				'unit' => $unit->getName (),
				'probability' => $probability,
				'level' => $this->getLevel (),
				'spell' => $html ? $this->getDisplayName () : $this->getName ()
			)
		);
	}
	
	public function procBuildingCost 	($resources, $objBuilding) { return $resources; }
	public function procBuildCost 		($resources, $objBuilding) { return $resources; }
	public function procUpgradeCost 	($resources, $objBuilding) { return $resources;  }
	public function procCapacity 		($resources, $objBuilding) { return $resources; }
	public function procIncome 		($resources, $objBuilding) { return $resources; }
	public function procEffectDifficulty 	($difficulty, $effect) { return $difficulty; }
	public function procDefenseBonus 	($def)	{ return $def; }
	public function procBattleVisible 	($battle) { return true; }
	public function procEquipmentDuration	($duration, $item) { return $duration; }
	public function procEquipmentCost	($cost, $item) { return $cost; }
	public function procUnitStats 		(&$stats, $unit) {}
	
	public function onBatteFought ($battle) {}
	
	public function procMoraleCheck 	($morale, $fight)
	{
		//$morale -= ($morale / 100) * $this->getBonusFromLevel ();
		$morale -= ($this->getBonusFromLevel () / 100);
	
		return $morale;
	}
	
	public function getCost ($objUnit, $objTarget, $cost = null)
	{
		switch ($this->getLevel ())
		{
			case 1:
			default:
				$gems = 200;
			break;
			
			case 2:
				$gems = 400;
			break;
			
			case 3:
				$gems = 650;
			break;
			
			case 4:
				$gems = 950;
			break;
			
			case 5:
				$gems = 1300;
			break;
		}
	
		if (!isset ($cost))
		{
			$cost = array
			(
				'gems' => $gems
			);
		}
		
		return $cost;
	}
	
	public function getDifficulty ($iBaseAmount = 40)
	{
		return 40;
	}
	
	protected function getMinimalBuildingLevel ()
	{
		return 4;
	}
}
?>
