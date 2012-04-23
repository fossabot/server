<?php
class BulkUploadResultEntry extends BulkUploadResult
{
    const CUSTOM_DATA_SSH_PRIVATE_KEY = 'sshPrivateKey';
    const CUSTOM_DATA_SSH_PUBLIC_KEY = 'sshPublicKey';
    const CUSTOM_DATA_SSH_KEY_PASSPHRASE = 'sshKeyPassphrase';
    
    //Entry property names
    const TITLE = "title";
    const DESCRIPTION = "description";
    const TAGS = "tags";
    const URL = "url";
    const CONTENT_TYPE = "content_type";
    const CONVERSION_PROFILE_ID = "conversion_profile_id";
    const ACCESS_CONSTROL_PROFILE_ID= "access_control_profile_id";
    const CATEGORY = "category";
    const SCHEDULE_START_DATE = "schedule_start_date";
    const SCHEDULE_END_DATE = "schedule_end_date";
    const THUMBNAIL_URL = "thumbnail_url";
    const THUMBNAIL_SAVED = "thumbnail_saved";
    const ENTRY_STATUS = "entry_status";
    
	
	/* (non-PHPdoc)
	 * @see BulkUploadResult::updateStatusFromObject()
	 */
	public function updateStatusFromObject()
	{
		$entry = entryPeer::retrieveByPKNoFilter($this->getObjectId());
		if(!$entry)
			return;
			
		$this->setEntryStatus($entry->getStatus());
		$this->save();
		
    	$closedStatuses = array (
    	    entryStatus::ERROR_IMPORTING,
			entryStatus::ERROR_CONVERTING,
			entryStatus::READY,
			entryStatus::DELETED,
			entryStatus::PENDING,
			entryStatus::NO_CONTENT,
    	);

		if(in_array($this->getObjectStatus(), $closedStatuses))
		{
			$this->updateEntryThumbnail();
		    $this->setStatus(BulkUploadResultStatus::OK);
		    $this->save();
			continue;
		}
			
		return $this->getStatus();
	}
	
    protected function updateEntryThumbnail(BulkUploadResult $bulkUploadResult)
	{
		if(		$this->getEntryStatus() != entryStatus::READY 
			||	!strlen($this->getThumbnailUrl()) 
			||	$this->getThumbnailSaved()
		)
			return;
			
		try 
		{
		    $entry = entryPeer::retrieveByPKNoFilter($this->getObjectId());
			myEntryUtils::updateThumbnailFromFile($entry, $this->getThumbnailUrl());
		}
		catch (Exception $e)
		{
			KalturaLog::err($e->getMessage());
			return;
		}
		
		$bulkUploadResult->setThumbnailSaved(true);
		$bulkUploadResult->save();
	}
	
	/* (non-PHPdoc)
	 * @see BulkUploadResult::handleRelatedObjects()
	 */
	public function handleRelatedObjects()
	{
	    $entry = $this->getObject(); 
		if(!$entry)
			throw new kCoreException("Entry not found");
			
		if($this->getThumbnailUrl())
			$entry->setCreateThumb(false);
			
		$entry->setBulkUploadId($this->getBulkUploadJobId());
		$entry->save();
	}
	
	
	/* (non-PHPdoc)
	 * @see BulkUploadResult::getObject()
	 */
	public function getObject()
	{
	    //Return deleted entries as well.
	    return entryPeer::retrieveByPKNoFilter($this->getObjectId());   
	}
	
	public function getEntryId()
	{
		if($this->getObjectType() == BulkUploadResultObjectType::ENTRY)
			return $this->getObjectId();
			
		return null;
	}

	
	public function setEntryId($v)
	{
		$this->setObjectType(BulkUploadResultObjectType::ENTRY);
		return $this->setObjectId($v);
	}
    
    public function getSshPrivateKey()		{return $this->getFromCustomData(self::CUSTOM_DATA_SSH_PRIVATE_KEY);}
	public function setSshPrivateKey($v)	{$this->putInCustomData(self::CUSTOM_DATA_SSH_PRIVATE_KEY, $v);}
	
    public function getSshPublicKey()		{return $this->getFromCustomData(self::CUSTOM_DATA_SSH_PUBLIC_KEY);}
	public function setSshPublicKey($v)	    {$this->putInCustomData(self::CUSTOM_DATA_SSH_PUBLIC_KEY, $v);}
	
    public function getSshKeyPassphrase()	{return $this->getFromCustomData(self::CUSTOM_DATA_SSH_KEY_PASSPHRASE);}
	public function setSshKeyPassphrase($v)	{$this->putInCustomData(self::CUSTOM_DATA_SSH_KEY_PASSPHRASE, $v);}
	
	//Set properties for entries
    public function getTitle()	{return $this->getFromCustomData(self::TITLE);}
	public function setTitle($v)	{$this->putInCustomData(self::TITLE, $v);}
	
    public function getDescription()	{return $this->getFromCustomData(self::DESCRIPTION);}
	public function setDescription($v)	{$this->putInCustomData(self::DESCRIPTION, $v);}

	public function getTags()	{return $this->getFromCustomData(self::TAGS);}
	public function setTags($v)	{$this->putInCustomData(self::TAGS, $v);}
	
	public function getUrl()	{return $this->getFromCustomData(self::URL);}
	public function setUrl($v)	{$this->putInCustomData(self::URL, $v);}
	
	public function getContentType()	{return $this->getFromCustomData(self::CONTENT_TYPE);}
	public function setContentType($v)	{$this->putInCustomData(self::CONTENT_TYPE, $v);}
	
	public function getConversionProfileId()	{return $this->getFromCustomData(self::CONVERSION_PROFILE_ID);}
	public function setConversionProfileId($v)	{$this->putInCustomData(self::CONVERSION_PROFILE_ID, $v);}
	
	public function getAcessControlProfileId()	{return $this->getFromCustomData(self::ACCESS_CONSTROL_PROFILE_ID);}
	public function setAccessControlProfileId($v)	{$this->putInCustomData(self::ACCESS_CONSTROL_PROFILE_ID, $v);}
	
	public function getCategory()	{return $this->getFromCustomData(self::CATEGORY);}
	public function setCategory($v)	{$this->putInCustomData(self::CATEGORY, $v);}
	
	public function getScheduleStartDate()	{return $this->getFromCustomData(self::SCHEDULE_START_DATE);}
	public function setScheduleStartDate($v)	{$this->putInCustomData(self::SCHEDULE_START_DATE, $v);}
	
	public function getScheduleEndDate()	{return $this->getFromCustomData(self::SCHEDULE_END_DATE);}
	public function setScheduleEndDate($v)	{$this->putInCustomData(self::SCHEDULE_END_DATE, $v);}
	
	public function getThumbnailUrl()	{return $this->getFromCustomData(self::THUMBNAIL_URL);}
	public function setThumbnailUrl($v)	{$this->putInCustomData(self::THUMBNAIL_URL, $v);}
	
	public function getThumbnailSaved()	{return $this->getFromCustomData(self::THUMBNAIL_SAVED);}
	public function setThumbnailSaved($v)	{$this->putInCustomData(self::THUMBNAIL_SAVED, $v);}
	
    public function getEntryStatus()	
    {
        return $this->getObjectStatus();
    }
    
	public function setEntryStatus($v)	
	{
	    $this->setObjectStatus($v);
	}
	
	
}