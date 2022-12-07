<?
AddEventHandler('sale', 'OnBeforeBasketAdd', 'OnBeforeBasketAddHandler');
AddEventHandler('sale', 'OnBeforeBasketUpdate', 'OnBeforeBasketUpdateHandler');
AddEventHandler('sale', 'OnBasketDelete', 'OnBasketDeleteHandler');
AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");
//Текущая корзина
function currentBasket()
{
    $arBasketItems = array();

    $dbBasketItems = CSaleBasket::GetList(
        array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
        array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL",
                "DELAY" => "N"
            ),
        false,
        false,
        array("ID", "CALLBACK_FUNC", "MODULE", 
            "PRODUCT_ID", "QUANTITY", "DELAY", 
            "CAN_BUY", "PRICE", "WEIGHT")
    );
    while ($arItems = $dbBasketItems->Fetch())
    {
        $arBasketItems[] = $arItems;
    }
    return $arBasketItems;
}

//Добавление 2-х бутылей воды при попытке добавить 1 
function OnBeforeBasketAddHandler(&$arFields)
{
    if(CModule::IncludeModule("sale"))
    {        
        if(CModule::IncludeModule("iblock"))
        {
            if ($arFields['PRODUCT_ID']) 
            {   
                $res =  CIBlockElement::GetByID($arFields["PRODUCT_ID"]); 
                if ($ar_res = $res->GetNext())
                {
                    $list = CIBlockSection::GetNavChain(false,$ar_res['IBLOCK_SECTION_ID'], array(), true);
                    foreach ($list as $arSectionPath)
                    {
                    $arSectionIds[] = $arSectionPath['ID'];
                    }
                    if (($ar_res['IBLOCK_ID'] == PRODUCT_CATALOG_ID && in_array(WATER19_SECTION_ID,$arSectionIds)) ||
                        ($ar_res['IBLOCK_ID'] == PRODUCT_CATALOG_ID_MOBILE && in_array(WATER19_SECTION_ID_MOBILE,$arSectionIds)) )
                        {
                            $arFields['QUANTITY'] = 2;
                        }

                } 
            }
        }
    }
}

// Минимум 2 бутыли к покупке при изменении корзины
function OnBeforeBasketUpdateHandler($ID,&$arFields)
{
    if (CModule::IncludeModule("sale")) 
    {

        if (CModule::IncludeModule("iblock") && $arFields['QUANTITY'] && $ID) 
        {
            
            //Текущая корзина
        $arBasketItems = currentBasket();
            foreach ($arBasketItems as $value) 
            {
               
                $res = CIBlockElement::GetByID($value["PRODUCT_ID"]);
                if ($ar_res = $res->GetNext()) 
                {
                    if ($ID == $value['ID']) {
                        $arSectionIds = [];
                        $list = CIBlockSection::GetNavChain(false, $ar_res['IBLOCK_SECTION_ID'], array(), true);
                        foreach ($list as $arSectionPath) {
                            $arSectionIds[] = $arSectionPath['ID'];
                        }
                        if (
                            ($ar_res['IBLOCK_ID'] == PRODUCT_CATALOG_ID && in_array(
                            WATER19_SECTION_ID,
                                $arSectionIds
                            ) && $arFields['QUANTITY'] == 1) ||
                            ($ar_res['IBLOCK_ID'] == PRODUCT_CATALOG_ID_MOBILE && in_array(
                            WATER19_SECTION_ID_MOBILE,
                                $arSectionIds
                            ) && $arFields['QUANTITY'] == 1)
                        ) {

                            $arFields['QUANTITY'] = 2;
                        }
                    }
                }
            }
        }
       
    }
}

// Минимум 2 бутыли при попытке удалении до 1
function OnBasketDeleteHandler(&$arFields)
{
    
    if(CModule::IncludeModule("sale"))
    { 
        //Текущая корзина
        $arBasketItems = currentBasket();
    
        $arElBasket19 = [];
        $arIdElem19Basket = [];
        if(CModule::IncludeModule("iblock"))
        {
            foreach ($arBasketItems as $value) 
            {   
                $res =  CIBlockElement::GetByID($value["PRODUCT_ID"]); 
                if ($ar_res = $res->GetNext())
                {
                    if (stripos($ar_res['DETAIL_PAGE_URL'], 'voda_19l/'))
                    {
                        $arElBasket19[] = $ar_res['DETAIL_PAGE_URL'];
                        if ($value['QUANTITY'] == 1)
                        {
                            $arIdElem19Basket[] = $value["ID"];
                        }
                    }
                } 
            }
        }
        //Количество 19-литровых в корзине
        $count19 = count($arElBasket19);
        $count19_1 = count($arIdElem19Basket);
        if ($count19 == 1 && $count19_1 == 1)
        {
            $arFields = ['QUANTITY' => 2];
            foreach ($arIdElem19Basket as $value) 
            {
                CSaleBasket::Update($value, $arFields);
            }
        }
    }
}

// Интеграция с CRM при изменении на сайте
function OnBeforeUserUpdateHandler($arFields) 
{
    $userName = $arFields['NAME'];
    $userPhone = $arFields['PERSONAL_PHONE'];
    $userEmail = $arFields['EMAIL'];
    
    $arClient = addCompanyCrm(
        $userName,
        $userPhone,
        $userEmail,
        true
    );
    $idCompany = $arClient['result'];
    AddMessage2Log('OnBeforeUserUpdateHandler');
    //При изменении даты рождения на сайте подставляем ее и в CRM
    //UF_CRM_1597046415735 - код поля "Дата рождения" в CRM
    $arAddResult = CRequest::exeCurlIncoming(
        "crm.company.update",
        [
            'id' => $idCompany,
            "fields" => [
                'UF_CRM_1597046415735' => $arFields['PERSONAL_BIRTHDAY'],
            ],
        ],
    );
}
?>
