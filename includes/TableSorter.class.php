<?
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Table Sorter Data Model
 *
 * @author Kobe Sun
 *
 */


class TableSorter
{
	static function returnMetaStr($start,$rows,$totalRows,$count,$orderBy='',$where='')
	{
		$nextStart = $start;
		if($nextStart<=0){
			$nextStart=0;
		}
		
		$nextRows = $start+$rows;
		
		if($totalRows-$start<$rows){
			$toRecord = $totalRows;
		}
		else{
			$toRecord = $start+$rows;
		}
		
		if($nextStart>0){
			$PagePrev = '<a id="PagePrev" title="'.$nextStart.'">&#171; Prev</a> &nbsp; <a>|</a> &nbsp;';
		}
		else{
			$PagePrev = '&#171; Prev &nbsp; | &nbsp;<span id="PagePrev"></span>';
		}

		if($count<$rows || $rows==0){
			$PageNext = 'Next &#187;<span id="PageNext"></span>';
		}
		else{
			$PageNext = '<a id="PageNext" title="'.($start+$rows).'">Next &#187;</a>';
		}
		
		return '<tr>
				<td colspan="10" class="MetaData">
					Records Found: '.$totalRows.' 
					&nbsp; // &nbsp; 
					Displaying '.$count.' records ('.$start.' - '.$toRecord.') 
					&nbsp;&nbsp; '.$PagePrev.' '.$PageNext.'
				</td></tr>';
	}
	
	static function returnSubMetaStr($start,$rows,$totalRows,$count,$orderBy='',$where='')
	{
		$nextStart = $start;
		if($nextStart<=0){
			$nextStart=0;
		}
		
		$nextRows = $start+$rows;
		
		if($totalRows-$start<$rows){
			$toRecord = $totalRows;
		}
		else{
			$toRecord = $start+$rows;
		}
		
		if($nextStart>0){
			$PagePrev = '<a id="SubPagePrev" title="'.$nextStart.'">&#171; Prev</a> &nbsp; <a>|</a> &nbsp;';
		}
		else{
			$PagePrev = '&#171; Prev &nbsp; | &nbsp;<span id="SubPagePrev"></span>';
		}
		
		if($count<$rows){
			$PageNext = 'Next &#187;<span id="SubPageNext"></span>';
		}
		else{
			$PageNext = '<a id="SubPageNext" title="'.($start+$rows).'">Next &#187;</a>';
		}
		
		return '<tr>
				<td colspan="10" class="MetaData">
					Records Found: '.$totalRows.' 
					&nbsp; // &nbsp; 
					Displaying '.$count.' records ('.$start.' - '.$toRecord.') 
					&nbsp;&nbsp; '.$PagePrev.' '.$PageNext.'
				</td></tr>';
	}
}
?>