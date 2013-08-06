<?php

/**
 * ZipHelper
 *
 * @autor Sebastian Klüh (http://sklueh.de)
 * @license LGPL
 *
 * Example:
 * $oZipHelper = new ZipHelper("my_archiv.zip");
 * 
 * $oZipHelper->addSource('*.txt')
 *   		  ->addSource('*.php')
 * 			  ->addSource('../')
 * 			  ->addSource('/test.txt')
 * 			  ->addSource(array('/home/sklueh/write.sh', 
 * 				   			    '/home/sklueh/config.php'))
 * 			  ->addSource('/home/sklueh/my_directory')
 * 			  ->create();
 */
class ZipHelper
{
	private $sFilename = "archiv.zip";
	private $aSources = array();
	private $oZipArchive = null;
	private $sCurrentParentPath = "";
	
	public function ZIPHelper($sFilename = "")
	{
		$this->oZipArchive = new ZipArchive();
		if($sFilename !== "")
		$this->sFilename = $sFilename;
	}
	
	public function addSource($sSource)
	{
		if(is_array($sSource))
		$this->aSources = array_merge($this->aSources, $sSource);
		else
		$this->aSources[] = $sSource;
		return $this; 			
	}
	
	public function create()
	{
		$this->oZipArchive->open($this->sFilename, ZIPARCHIVE::OVERWRITE);
		foreach((array)$this->aSources as $sSource)
		{
			$this->sCurrentParentPath = dirname(realpath($sSource));
			if(is_dir($sSource))
			$this->iterateDir($sSource);
			elseif(is_file($sSource))
			$this->oZipArchive->addFile($sSource, basename($sSource));
			else
			foreach((array)glob($sSource) as $sFoundSource)
			{
				$this->oZipArchive->addFile($sFoundSource, $sFoundSource);
			}
		}
		$this->oZipArchive->close();
	}
	
	private function iterateDir($sPath)
	{
	    foreach(new DirectoryIterator($sPath) as $oItem)
	    {
	        if($oItem->isDir())
	        {
	        	if(!$oItem->isDot())
				$this->iterateDir($oItem->getPathname());				
	            continue;
	        }
			$this->oZipArchive->addFile($oItem->getPathname(), $this->removeParentDir($oItem->getPathname()));
	    }
	}
	
	private function removeParentDir($sPath)
	{
		return str_replace($this->correctPath($this->sCurrentParentPath)."/", "", $this->correctPath(realpath($sPath)));
	}
	
	private function correctPath($sTargetPath)
  {
    return str_replace("//", "/",
           str_replace("\\", "/", $sTargetPath));
  }
}
?>
