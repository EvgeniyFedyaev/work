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
<?$this->SetViewTarget('head_news');?>
	<div class="col-12">
		<div class="dabl-headings">
			<h1 class="page-main-heading"><?=$arResult['NAME']?></h1>
		</div>
		<div class="tags-wrapper">
			<div class="date"><?=$arResult['PROPERTIES']['DATE_PUBLIC']['VALUE']?></div>
			<ul class="tags-list">
				<?foreach ($arResult['PROPERTIES']['TEGS']['VALUE'] as $key => $value):?>
					<li class="tags-list__item">
						<a href="#"><?=$value?></a>
					</li>
				<?endforeach;?>
			</ul>
		</div>
	</div>
<?$this->EndViewTarget();?> 
<div class="col-lg-8" style="z-index: 3;">
	<div class="post-content">
		<h2 class="post-content__heading"><?=$arResult['~PREVIEW_TEXT']?></h2>
		<div class="post-content__thumbnail">
			<img src="<?=$arResult['DETAIL_PICTURE']?>" alt="">
		</div>
		
		<?=$arResult['~DETAIL_TEXT']?>
	</div>
	<div class="post-subscribe">
		<div class="post-subscribe__title"><?=getMessage('SHARE');?></div>
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "inc",
				"EDIT_TEMPLATE" => "",
				"PATH" => "/include/subscribe.php"
			)
		);?>
	</div>
</div>
			
		