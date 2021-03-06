<?php
class SphinxTagCriteria extends SphinxCriteria
{
	/* (non-PHPdoc)
	 * @see SphinxCriteria::getIndexObjectName()
	*/
	public function getIndexObjectName() {
		return "TagIndex";
	}
	
	protected function applyFilterFields(baseObjectFilter $filter)
	{
		if ($filter->get('_eq_object_type'))
		{
			$filter->set('_eq_object_type', Tag::getIndexedFieldValue('TagPeer::OBJECT_TYPE', $filter->get('_eq_object_type'), kCurrentContext::getCurrentPartnerId()));
		}
		
		parent::applyFilterFields($filter);
	}
	
	public function translateSphinxCriterion(SphinxCriterion $crit)
	{
		$field = $crit->getTable() . '.' . $crit->getColumn();
		$value = $crit->getValue();
		
		$fieldName = null;
		if ($field == TagPeer::OBJECT_TYPE)
			$fieldName = "TagPeer::OBJECT_TYPE";
		if ($field == TagPeer::PRIVACY_CONTEXT)
			$fieldName = "TagPeer::PRIVACY_CONTEXT";

		if ($fieldName)
		{
			$partnerIdCrit = $this->getCriterion(TagPeer::PARTNER_ID);
			if ($partnerIdCrit && $partnerIdCrit->getComparison() == Criteria::EQUAL)
				$partnerId = $partnerIdCrit->getValue();
			else
				$partnerId = kCurrentContext::getCurrentPartnerId();
			
			$value = Tag::getIndexedFieldValue($fieldName, $value, $partnerId);
		}
		
		if ($field == TagPeer::TAG && in_array($crit->getComparison(), array(Criteria::EQUAL, Criteria::IN)))
		{
			$value = str_replace(kTagFlowManager::$specialCharacters, kTagFlowManager::$specialCharactersReplacement, $value);
		}

		return array($field, $crit->getComparison(), $value);
	}
	
	public function hasPeerFieldName($fieldName)
	{
		if(strpos($fieldName, '.') === false)
		{
			$fieldName = strtoupper($fieldName);
			$fieldName = "tag.$fieldName";
		}
	
		$entryFields = TagPeer::getFieldNames(BasePeer::TYPE_COLNAME);
	
		return in_array($fieldName, $entryFields);
	}
	
}