<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()

$strReturn .= '<ul>';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > 0? '<i class="fa fa-angle-right"></i>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1 && $index < ($itemSize - 1))
	{
		$strReturn .= '
			<li>
				<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="item">'.$title.'
				</a>
			</li>
			<li>
		 	<span class="delim">/</span>
		 	</li>';
	}
	else
	{
		$strReturn .= '
			<li title="'.$title.'">'.$title.'
			</li>';
	}
}

$strReturn .= '</ul>';

return $strReturn;
