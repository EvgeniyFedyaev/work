<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="site-sidebar">
	<h3 class="site-sidebar__title"><?=getMessage('RECOMMEND');?></h3>
	<div class="site-sidebar-body">
		<?foreach ($arResult['ITEMS'] as $item):?>
			<a href="#" class="site-sidebar-body__item">
				<ul class="category-wrapper">
					<?foreach ($item['PROPERTIES']['CATEGORIES']['VALUE'] as $value):?>
						<li class="category-wrapper__item"><?=$value?></li>
					<?endforeach;?>
				</ul>
				<h4 class="title"><?=$item['PREVIEW_TEXT']?></h4>
				<span class="date"><?=$item['PROPERTIES']['DAYR_PUBLIC']['VALUE']?></span>
			</a>
		<?endforeach;?>
	</div>
</div>
