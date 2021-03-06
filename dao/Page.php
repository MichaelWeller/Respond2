<?php

// Page model
class Page{
    
	// adds a page
	public static function Add($friendlyId, $name, $description, $layout, $stylesheet, $pageTypeId, $siteId, $createdBy){
		
        try{
            
            $db = DB::get();
    		
    		$pageUniqId = uniqid();
            
            // cleanup friendlyid (escape, trim, remove spaces, tolower)
        	$friendlyId = trim($friendlyId);
    		$friendlyId = str_replace(' ', '', $friendlyId);
    		$friendlyId = strtolower($friendlyId);
    
    		// set defaults
    		$isActive = 0;
    		$image = '';
    		$lastModifiedBy = $createdBy;
    		$keywords = '';
    		$callout = '';
    		$rss = '';
    		$timestamp = gmdate("Y-m-d H:i:s", time());
    	
    		$q = "INSERT INTO Pages (PageUniqId, FriendlyId, Name, Description, Keywords, Callout, Rss, Layout, Stylesheet, PageTypeId, SiteId, CreatedBy, LastModifiedBy, LastModifiedDate, IsActive, Image, Created) 
    			    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $s = $db->prepare($q);
            $s->bindParam(1, $pageUniqId);
            $s->bindParam(2, $friendlyId);
            $s->bindParam(3, $name);
            $s->bindParam(4, $description);
            $s->bindParam(5, $keywords);
            $s->bindParam(6, $callout);
            $s->bindParam(7, $rss);
            $s->bindParam(8, $layout);
            $s->bindParam(9, $stylesheet);
            $s->bindParam(10, $pageTypeId);
            $s->bindParam(11, $siteId);
            $s->bindParam(12, $createdBy);
            $s->bindParam(13, $lastModifiedBy);
            $s->bindParam(14, $timestamp);
            $s->bindParam(15, $isActive);
            $s->bindParam(16, $image);
            $s->bindParam(17, $timestamp);
            
            $s->execute();
            
            return array(
                'PageId' => $db->lastInsertId(),
                'PageUniqId' => $pageUniqId,
                'Name' => $name,
                'Description' => $description,
                'Keywords' => $keywords,
                'Callout' => $callout,
                'Rss' => $rss,
                'Layout' => $layout,
                'Stylesheet' => $stylesheet,
                'PageTypeId' => $pageTypeId,
                'SiteId' => $siteId,
                'CreatedBy' => $createdBy,
                'LastModifiedBy' => $lastModifiedBy,
                'Created' => $timestamp,
                'IsActive' => $isActive,
                'Image' => $image,
                'Thumb' => '',
                'LastModifiedDate' => $timestamp,
                );
                
        } catch(PDOException $e){
            die('[Page::Add] PDO Error: '.$e->getMessage());
        }
	}
	
	// determines whether a friendlyId is unique
	public static function IsFriendlyIdUnique($friendlyId, $siteId){
        
        try{

            $db = DB::get();
            
            // cleanup friendlyid (escape, trim, remove spaces, tolower)
        	$friendlyId = trim($friendlyId);
    		$friendlyId = str_replace(' ', '', $friendlyId);
    		$friendlyId = strtolower($friendlyId);
    
        	$count = 0;
    	
    		$q ="SELECT Count(*) as Count FROM Pages where FriendlyId = ? AND SiteId=?";
    
        	$s = $db->prepare($q);
            $s->bindParam(1, $friendlyId);
            $s->bindParam(1, $siteId);
            
    		$s->execute();
    
    		$count = $s->fetchColumn();
    
    		if($count==0){
    			return true;
    		}
    		else{
    			return false;
    		}
            
        } catch(PDOException $e){
            die('[Page::IsFriendlyIdUnique] PDO Error: '.$e->getMessage());
        } 
	}
	
	// edits a page
	public static function EditImage($pageUniqId, $image, $lastModifiedBy){
		
        try{
            
            $db = DB::get();
            
    	    $timestamp = gmdate("Y-m-d H:i:s", time());
            
            $q = "UPDATE Pages SET
    				Image = ?,
					LastModifiedBy = ?,
					LastModifiedDate = ?
					WHERE PageUniqId = ?";
     
            $s = $db->prepare($q);
            $s->bindParam(1, $image);
            $s->bindParam(2, $lastModifiedBy);
            $s->bindParam(3, $timestamp);
            $s->bindParam(4, $pageUniqId);
            
            $s->execute();
            
		} catch(PDOException $e){
            die('[Page::EditImage] PDO Error: '.$e->getMessage());
        }
	}
	
	// edits the timestamp for a page
	public static function EditTimestamp($pageUniqId, $lastModifiedBy){
		
        try{
            
            $db = DB::get();
            
    	    $timestamp = gmdate("Y-m-d H:i:s", time());
            
            $q = "UPDATE Pages SET
					LastModifiedBy = ?,
					LastModifiedDate = ?
					WHERE PageUniqId = ?";
     
            $s = $db->prepare($q);
            $s->bindParam(1, $lastModifiedBy);
            $s->bindParam(2, $timestamp);
            $s->bindParam(3, $pageUniqId);
            
            $s->execute();
            
		} catch(PDOException $e){
            die('[Page::EditTimestamp] PDO Error: '.$e->getMessage());
        }
	}

	// edits the settings for a page
	public static function EditSettings($pageUniqId, $name, $friendlyId, $description, $keywords, $callout, $rss, $layout, $stylesheet, $lastModifiedBy){
		
        try{
            
            $db = DB::get();
            
            $timestamp = gmdate("Y-m-d H:i:s", time());
            
            $q = "UPDATE Pages 
    				SET Name = ?, 
					FriendlyId = ?, 
					Description = ?, 
					Keywords = ?, 
					Callout = ?, 
					Rss = ?, 
					Layout = ?, 
					Stylesheet = ?, 
					LastModifiedBy = ?, 
					LastModifiedDate = ? 
					WHERE PageUniqId = ?";
     
            $s = $db->prepare($q);
            $s->bindParam(1, $name);
            $s->bindParam(2, $friendlyId);
            $s->bindParam(3, $description);
            $s->bindParam(4, $keywords);
            $s->bindParam(5, $callout);
            $s->bindParam(6, $rss);
            $s->bindParam(7, $layout);
            $s->bindParam(8, $stylesheet);
            $s->bindParam(9, $lastModifiedBy);
            $s->bindParam(10, $timestamp);
            $s->bindParam(11, $pageUniqId);
            
            $s->execute();
            
		} catch(PDOException $e){
            die('[Page::EditSettings] PDO Error: '.$e->getMessage());
        }
	}
	
	// edits the settings for a page
	public static function EditLayout($pageUniqId, $layout, $lastModifiedBy){
		
        try{
            
            $db = DB::get();
            
            $timestamp = gmdate("Y-m-d H:i:s", time());
            
            $q = "UPDATE Pages 
    				SET  
					Layout = ?, 
					LastModifiedBy = ?, 
					LastModifiedDate = ? 
					WHERE PageUniqId = ?";
     
            $s = $db->prepare($q);
            $s->bindParam(1, $layout);
            $s->bindParam(2, $lastModifiedBy);
            $s->bindParam(3, $timestamp);
            $s->bindParam(4, $pageUniqId);
            
            $s->execute();
            
		} catch(PDOException $e){
            die('[Page::EditLayout PDO Error: '.$e->getMessage());
        }
	}
	
	// edits IsActive
	public static function SetIsActive($pageUniqId, $isActive){
	
        try{
            
            $db = DB::get();
            
            $q = "UPDATE Pages 
            		SET IsActive = ? 
					WHERE PageUniqId = ?";
     
            $s = $db->prepare($q);
            $s->bindParam(1, $isActive);
            $s->bindParam(2, $pageUniqId);
            
            $s->execute();
            
		} catch(PDOException $e){
            die('[Page::SetIsActive] PDO Error: '.$e->getMessage());
        }
	}
	
	// removes a page
	public static function Remove($pageUniqId){
		
        try{
            
            $db = DB::get();
            
            $q = "DELETE FROM Pages WHERE PageUniqId = ?";
     
            $s = $db->prepare($q);
            $s->bindParam(1, $pageUniqId);
            
            $s->execute();
            
		} catch(PDOException $e){
            die('[Page::Remove] PDO Error: '.$e->getMessage());
        }
        
	}
	
	// gets all pages
	public static function GetPages($siteId, $pageTypeId, $pageSize, $pageNo, $orderBy, $activeOnly = false){
		
        try{

            $db = DB::get();
            
            $activeClause = '';

        	if($activeOnly==true){
    			$activeClause = ' AND IsActive=1';
    		}
    		
    		$next = $pageSize * $pageNo;
    
            $q = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, 
            		Pages.Description, Pages.Keywords, Pages.Callout,
        			Pages.Layout, Pages.Stylesheet, Pages.RSS,
        			Pages.SiteId, Pages.CreatedBy, 
        			Pages.LastModifiedBy, Pages.Created, Pages.LastModifiedDate, 
        			Pages.IsActive, Pages.Image, Pages.PageTypeId,
        			Users.FirstName, Users.LastName
        			FROM Pages LEFT JOIN Users ON Pages.LastModifiedBy = Users.UserId
        			WHERE Pages.SiteId = ? AND Pages.PageTypeId = ?".$activeClause." ORDER BY ".$orderBy." LIMIT ?, ?";
        			
            $s = $db->prepare($q);
            $s->bindParam(1, $siteId);
            $s->bindParam(2, $pageTypeId);
            $s->bindValue(3, intval($next), PDO::PARAM_INT);
            $s->bindValue(4, intval($pageSize), PDO::PARAM_INT);
            
            $s->execute();
            
            $arr = array();
            
            while($row = $s->fetch(PDO::FETCH_ASSOC)) {  
                array_push($arr, $row);
            } 
            
            return $arr;
        
		} catch(PDOException $e){
            die('[Page::GetPages]'.'[next='.$next.'pageSize='.$pageSize.']---PDO Error: '.$e->getMessage().'trace='.$e->getTraceAsString());
        } 
        
	}

	// get the total number of pages
	public static function GetPagesCount($siteId, $pageTypeId, $activeOnly = false){
		
        try{

            $db = DB::get();
            
            $activeClause = '';

        	if($activeOnly==true){
    			$activeClause = ' AND IsActive=1';
    		}
    
        	$count = 0;
    	
    		$q = "SELECT Count(*) as Count
    		        FROM Pages
			        WHERE SiteId = ? AND PageTypeId = ?".$activeClause;
    
        	$s = $db->prepare($q);
            $s->bindParam(1, $siteId);
            $s->bindParam(2, $pageTypeId);
            
    		$s->execute();
    
    		$count = $s->fetchColumn();
    
    		return $count;
            
        } catch(PDOException $e){
            die('[Page::GetPagesCount] PDO Error: '.$e->getMessage());
        }
        
	}

	// Gets all 
	public static function GetPagesForSite($siteId, $activeOnly = false){
		
        try{

            $db = DB::get();
            
            $activeClause = '';

            if($activeOnly==true){
    			$activeClause = ' AND IsActive=1';
    		}
    		
            $q = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, 
            		Pages.Description, Pages.Keywords, Pages.Callout,
        			Pages.Layout, Pages.Stylesheet, Pages.RSS,
        			Pages.SiteId, Pages.CreatedBy, 
        			Pages.LastModifiedBy, Pages.Created, Pages.LastModifiedDate, 
        			Pages.IsActive, Pages.Image, Pages.PageTypeId,
        			Users.FirstName, Users.LastName 
        			FROM Pages LEFT JOIN Users ON Pages.LastModifiedBy = Users.UserId
        			WHERE Pages.SiteId = ?".$activeClause.
        			" ORDER BY Pages.Name ASC";
                    
            $s = $db->prepare($q);
            $s->bindParam(1, $siteId);
            
            $s->execute();
            
            $arr = array();
            
            while($row = $s->fetch(PDO::FETCH_ASSOC)) {  
                array_push($arr, $row);
            } 
            
            return $arr;
        
		} catch(PDOException $e){
            die('[Page::GetPagesForSite] PDO Error: '.$e->getMessage());
        }
        
	}
	
	// gets all page for a given $site, pageTypeId
	public static function GetPagesForPageType($siteId, $pageTypeId){

        try{

            $db = DB::get();
		    
            $q = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Description, Pages.Callout,
            		Pages.SiteId, Pages.CreatedBy, 
        			Pages.LastModifiedBy, Pages.Created, Pages.LastModifiedDate, 
        			Pages.IsActive, Pages.Image, Pages.PageTypeId,
        			Users.FirstName, Users.LastName
        			FROM Users, Pages
        			WHERE Pages.LastModifiedBy = Users.UserId AND Pages.SiteId = ? AND Pages.PageTypeId = ?
        			ORDER BY Pages.Name ASC";
                  
            $s = $db->prepare($q);
            $s->bindParam(1, $siteId);
            $s->bindParam(2, $pageTypeId);
            
            $s->execute();
            
            $arr = array();
            
            while($row = $s->fetch(PDO::FETCH_ASSOC)) {  
                array_push($arr, $row);
            } 
            
            return $arr;
        
		} catch(PDOException $e){
            die('[Page::GetPagesForPageType] PDO Error: '.$e->getMessage());
        } 
        
	}
	
	// gets all pages for a given $site, pageTypeId
	public static function GetRSS($siteId, $pageTypeId){
		
        try{

            $db = DB::get();
    	    
            $q = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Description,
            		Pages.SiteId, Pages.CreatedBy, 
        			Pages.LastModifiedBy, Pages.Created, Pages.LastModifiedDate, 
        			Pages.IsActive, Pages.Image, Pages.PageTypeId,
        			Users.FirstName, Users.LastName
        			FROM Users, Pages
        			WHERE Pages.CreatedBy = Users.UserId AND Pages.SiteId = ? AND Pages.PageTypeId = ?
        			ORDER BY Pages.Created DESC";
                    
            $s = $db->prepare($q);
            $s->bindParam(1, $siteId);
            $s->bindParam(2, $pageTypeId);
            
            $s->execute();
            
            $arr = array();
            
            while($row = $s->fetch(PDO::FETCH_ASSOC)) {  
                array_push($arr, $row);
            } 
            
            return $arr;
        
		} catch(PDOException $e){
            die('[Page::GetRSS] PDO Error: '.$e->getMessage());
        }
        
	}
	
	
	// gets a page for a specific $pageUniqId
	public static function GetByPageUniqId($pageUniqId){

        try{
        
        	$db = DB::get();
            
            $q = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Description, Pages.Keywords, 
            		Pages.Callout, Pages.Rss,
        			Pages.Layout, Pages.Stylesheet,
        			Pages.PageTypeId, Pages.SiteId, Pages.CreatedBy, Pages.LastModifiedBy, Pages.LastModifiedDate,  
        			Pages.IsActive, Pages.Image, Pages.Created
        		 	FROM Pages WHERE PageUniqId=?";
                    
            $s = $db->prepare($q);
            $s->bindParam(1, $pageUniqId);
            
            $s->execute();
            
            $row = $s->fetch(PDO::FETCH_ASSOC);        
    
    		if($row){
    			return $row;
    		}
        
        } catch(PDOException $e){
            die('[Page::GetByPageUniqId] PDO Error: '.$e->getMessage());
        }
        
	}
	
	// gets a page for a specific $friendlyId and $siteId
	public static function GetByFriendlyId($friendlyId, $siteId){
		
        try{
        
            $db = DB::get();
            
            $q = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Description, Pages.Keywords, 
            		Pages.Callout, Pages.Rss,
        			Pages.Layout, Pages.Stylesheet,
        			Pages.PageTypeId, Pages.SiteId, Pages.CreatedBy, Pages.LastModifiedBy, Pages.LastModifiedDate,  
        			Pages.IsActive, Pages.Image, Pages.Created
        		 	FROM Pages WHERE FriendlyId=? AND SiteId=?";
                    
            $s = $db->prepare($q);
            $s->bindParam(1, $friendlyId);
            $s->bindParam(1, $siteId);
            
            $s->execute();
            
            $row = $s->fetch(PDO::FETCH_ASSOC);        
    
    		if($row){
    			return $row;
    		}
        
        } catch(PDOException $e){
            die('[Page::GetByFriendlyId] PDO Error: '.$e->getMessage());
        }
        
	}
	
	// gets a page for a specific $pageId
	public static function GetByPageId($pageId){
	
        try{
        
            $db = DB::get();
            
            $q = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Description, Pages.Keywords, 
            		Pages.Callout, Pages.Rss,
        			Pages.Layout, Pages.Stylesheet,
        			Pages.PageTypeId, Pages.SiteId, Pages.CreatedBy, Pages.LastModifiedBy, Pages.LastModifiedDate,  
        			Pages.IsActive, Pages.Image, Pages.Created
        		 	FROM Pages WHERE PageId=?";
                    
            $s = $db->prepare($q);
            $s->bindParam(1, $pageId);
            
            $s->execute();
            
            $row = $s->fetch(PDO::FETCH_ASSOC);        
    
    		if($row){
    			return $row;
    		}
        
        } catch(PDOException $e){
            die('[Page::GetByPageId] PDO Error: '.$e->getMessage());
        }
        
	}
	
}

?>