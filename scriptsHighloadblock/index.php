<
// Скрипт для записи/удаления метатегов, размещенных в highloadblock-е и содержащихся в excel-файлах в этой же папке
?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?
$APPLICATION->SetTitle("Настройка связи с мобильной версией");
// Подразумевается библиотека PHPExcek в этой же папке
require_once __DIR__ . '/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';

use Bitrix\Main\Loader; 

Loader::includeModule("highloadblock"); 

use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

$excel = PHPExcel_IOFactory::load(__DIR__ . '/new_alternate_add.xlsx');

//Перебираем все листы
foreach ($excel->getWorksheetIterator() as $worksheet) 
{
    $lists[] = $worksheet->toArray();
}
echo '<pre>';
$arLinkDesktop = [];
$arLinkMobile = [];
foreach($lists[0] as $list)
{
    $strList = htmlspecialchars($list[2]);
        $s1 = htmlspecialchars('<link rel="alternate" media="only screen and (max-width: 800px)" href="');
        $s2 = htmlspecialchars('"/>');
        $cleanUrlDesk = str_replace($s1, "", $strList);//Чистим адреса для HIblock-а
        $cleanUrlDesk = str_replace($s2, "", $cleanUrlDesk);//Чистим адреса для HIblock-а
        if ($list[2] != "")
        {
            $cleanUrlDesk .= "/";
            $arLinkMobile[] = $cleanUrlDesk;//str_replace("https://" . SITE_SERVER_NAME, "", $list[0]);//Чистим адреса для HIblock-а
        }

        if ($list[0] != "")
        {
            $cleanUrl = str_replace("https://" . SITE_SERVER_NAME, "", $list[0]);//Чистим адреса для HIblock-а
            if ($cleanUrl == "") $cleanUrl = "/";
            $arLinkDesktop[] = $cleanUrl;
        }
}

print_r($arLinkMobile);
echo '</pre>';
//ID HIblock-а
$hlbl = 1;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
$entity_data_class = $entity->getDataClass();

// Массив полей для добавления
  
for ($i = 1; $i <= count($arLinkMobile); $i++) 
{ 
    // Коды полей HIblock-а
    $data = array(
        "UF_URL"=>$arLinkDesktop[$i],
        "UF_ALTERNATE"=>$arLinkMobile[$i],
     );
    
     $result = $entity_data_class::add($data);//Добавление
}



foreach ($arLinkDesktop as $linkDesktop) //Перебираем эл-ты и удаляем совпадающие
{
    $rsData = $entity_data_class::getList(array
        (
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_URL" => "$linkDesktop")  // Задаем параметры фильтра выборки
        )
    );
    if ($arData = $rsData->Fetch())
    {
        // Удаление лишних элементов
        for ($i = 285; $i < 1737; $i++) { 
            $entity_data_class::Delete($i);
        }
        $entity_data_class::Delete($arData["ID"]);
    }
}


Вывод таблицей
foreach($lists as $list)
{
    echo '<table border="1">';
    // Перебор строк
    foreach($list as $row)
    {
        echo '<tr>';
        // Перебор столбцов
        foreach($row as $col)
        {
            echo '<td>'.$col.'</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
