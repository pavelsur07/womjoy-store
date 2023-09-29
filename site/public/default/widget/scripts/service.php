<?php

declare(strict_types=1);

namespace {
    use Symfony\Component\Dotenv\Dotenv;

    // import vendors
    require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

    // load env files
    (new Dotenv())->bootEnv(dirname(__DIR__, 3) . '/.env');

    header('Access-Control-Allow-Origin: *');
    error_reporting(63);

    SDEKService\Controller::processRequest(
        SDEKService\Settings::factory(
            /** Настройте приоритет тарифов курьерской доставки */
            /** Set up the priority of courier delivery tariffs */
            [233, 137, 139, 16, 18, 11, 1, 3, 61, 60, 59, 58, 57, 83],
            /** Настройте приоритет тарифов доставки до пунктов выдачи */
            /** Set the priority of delivery tariffs to pick-up points */
            [234, 136, 138, 15, 17, 10, 12, 5, 62, 63],
            /** Вставьте свой аккаунт\идентификатор для интеграции */
            /** Put your account for integration here */
            $_ENV['CDEK_ACCOUNT'] ?? '',
            /** Вставьте свой пароль для интеграции */
            /** Put your password for integration here */
            $_ENV['CDEK_PASSWORD'] ?? '',
        )
    );
}

namespace SDEKService {
    class Settings
    {
        public const COURIER_TARIFF_PRIORITY = 'courier';

        public const PICKUP_TARIFF_PRIORITY = 'pickup';

        /**
         * @var array
         */
        private $courierTariffPriority;
        /**
         * @var array
         */
        private $pickupTariffPriority;
        /**
         * @var bool|string
         */
        private $account;
        /**
         * @var bool|string
         */
        private $key;

        /**
         * @param array $courierTariffPriority
         * @param array $pickupTariffPriority
         * @param bool|string $account
         * @param bool|string $key
         */
        private function __construct($courierTariffPriority, $pickupTariffPriority, $account, $key)
        {
            $this->courierTariffPriority = $courierTariffPriority;
            $this->pickupTariffPriority = $pickupTariffPriority;
            $this->account = $account ?: false;
            $this->key = $key ?: false;
        }

        /**
         * @param array $courierTariffPriority
         * @param array $pickupTariffPriority
         * @param bool|string $account
         * @param bool|string $key
         * @return static
         */
        public static function factory($courierTariffPriority, $pickupTariffPriority, $account, $key)
        {
            return new self($courierTariffPriority, $pickupTariffPriority, $account, $key);
        }

        /**
         * @param string|null $type
         * @return array - all or concrete tariffs priority
         * @throws \InvalidArgumentException
         */
        public function getTariffPriority($type = self::COURIER_TARIFF_PRIORITY)
        {
            if (!\in_array($type, [self::COURIER_TARIFF_PRIORITY, self::PICKUP_TARIFF_PRIORITY], true)) {
                throw new \InvalidArgumentException("Unknown tariff type {$type}");
            }

            return $type === self::COURIER_TARIFF_PRIORITY ? $this->courierTariffPriority : $this->pickupTariffPriority;
        }

        /**
         * @return bool
         */
        public function hasCredentials()
        {
            return $this->account && $this->key;
        }

        /**
         * @return bool|string
         */
        public function getAccount()
        {
            return $this->account;
        }

        /**
         * @return bool|string
         */
        public function getKey()
        {
            return $this->key;
        }
    }

    /** base actions class */
    abstract class BaseAction
    {
        /**
         * @var Controller
         */
        protected $controller;

        /**
         * BaseAction constructor.
         */
        public function __construct(Controller $controller)
        {
            $this->controller = $controller;
        }

        /**
         * @return array|mixed result data for response
         */
        abstract public function run();

        /**
         * @param string $url
         * @param array|bool|string $data
         * @param bool $rawRequest
         * @return array
         */
        protected function sendCurlRequest($url, $data = false, $rawRequest = false)
        {
            if (!\function_exists('curl_init')) {
                return ['error' => 'No php CURL-library installed on server'];
            }

            $curlOptions = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
            ];

            if ($rawRequest) {
                $curlOptions[CURLOPT_POST] = false;
                $curlOptions[CURLOPT_HTTPHEADER] = ['Content-type: application/json'];
            }

            if ($data) {
                $curlOptions += [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_REFERER => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'],
                ];
            }

            $ch = curl_init();
            curl_setopt_array($ch, $curlOptions);
            $result = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return [
                'code' => $code,
                'result' => $result,
            ];
        }
    }

    /** pvz */
    class PickupAction extends BaseAction
    {
        /**
         * @return array|string[]
         */
        public function run()
        {
            if (!\function_exists('simplexml_load_string')) {
                return ['error' => 'No php simplexml-library installed on server'];
            }
            $mode = $this->controller->getRequestValue('mode');
            $langPart = $this->controller->getRequestValue('lang') ? '&lang=' . $this->controller->getRequestValue('lang') : '';
            $request = $this->sendCurlRequest('https://integration.cdek.ru/pvzlist/v1/xml?type=ALL' . $langPart);

            if ($request && $request['code'] === 200) {
                $xml = simplexml_load_string($request['result']);

                $arList = ['PVZ' => [], 'CITY' => [], 'REGIONS' => [], 'CITYFULL' => [], 'COUNTRIES' => []];
                foreach ($xml as $key => $val) {
                    if ($mode !== 'all') {
                        if (strtolower((string)$val['Type']) !== $mode) {
                            continue;
                        }
                    }

                    if (($country = $this->controller->getRequestValue('country'))
                        && $country !== 'all'
                        && ((string)$val['CountryName'] !== $country)) {
                        continue;
                    }

                    $cityCode = (string)$val['CityCode'];
                    $type = 'PVZ';

                    $city = (string)$val['City'];
                    if (str_contains($city, '(')) {
                        $city = trim(mb_substr($city, 0, strpos($city, '(')));
                    }
                    if (str_contains($city, ',')) {
                        $city = trim(mb_substr($city, 0, strpos($city, ',')));
                    }
                    $code = (string)$val['Code'];

                    $arList[$type][$cityCode][$code] = [
                        'Name' => (string)$val['Name'],
                        'WorkTime' => (string)$val['WorkTime'],
                        'Address' => (string)$val['Address'],
                        'Phone' => (string)$val['Phone'],
                        'Note' => (string)$val['Note'],
                        'cX' => (string)$val['coordX'],
                        'cY' => (string)$val['coordY'],
                        'Dressing' => ((string)$val['IsDressingRoom'] === 'true'),
                        'Cash' => ((string)$val['HaveCashless'] === 'true'),
                        'Postamat' => (strtolower((string)$val['Type']) === 'postamat'),
                        'Station' => (string)$val['NearestStation'],
                        'Site' => (string)$val['Site'],
                        'Metro' => (string)$val['MetroStation'],
                        'AddressComment' => (string)$val['AddressComment'],
                        'CityCode' => (string)$val['CityCode'],
                    ];

                    if ($val->WeightLimit) {
                        $arList[$type][$cityCode][$code]['WeightLim'] = [
                            'MIN' => (float)$val->WeightLimit['WeightMin'],
                            'MAX' => (float)$val->WeightLimit['WeightMax'],
                        ];
                    }

                    $arImgs = [];

                    foreach ($val->OfficeImage as $img) {
                        if (!str_contains($_tmpUrl = (string)$img['url'], 'http')) {
                            continue;
                        }
                        $arImgs[] = (string)$img['url'];
                    }

                    if (\count($arImgs = array_filter($arImgs))) {
                        $arList[$type][$cityCode][$code]['Picture'] = $arImgs;
                    }
                    if ($val->OfficeHowGo) {
                        $arList[$type][$cityCode][$code]['Path'] = (string)$val->OfficeHowGo['url'];
                    }

                    if (!\array_key_exists($cityCode, $arList['CITY'])) {
                        $arList['CITY'][$cityCode] = $city;
                        $arList['CITYREG'][$cityCode] = (int)$val['RegionCode'];
                        $arList['REGIONSMAP'][(int)$val['RegionCode']][] = (int)$cityCode;
                        $arList['CITYFULL'][$cityCode] = $val['CountryName'] . ' ' . $val['RegionName'] . ' ' . $city;
                        $arList['REGIONS'][$cityCode] = implode(', ', array_filter([(string)$val['RegionName'], (string)$val['CountryName']]));
                    }

                    //                    (Щербинка)162, (Внуковское)42932, (Воскресенское)13369, (Кокошкино)1690, (Московский)469,
                    //                    (Мосрентген)1198, (Сосенское)75809, (Троицк)510, (Киевский)1689, (Первомайское)16338

                    if (\in_array($cityCode, [162, 13369, 1690, 469, 1198, 510, 1689, 16338], true)) {
                        $arList[$type][$cityCode . '_distance'] = $this->getPvzDistance($cityCode);
                    }
                }

                krsort($arList['PVZ']);

                return ['pvz' => $arList];
            }

            if ($request) {
                return ['error' => 'Wrong answer code from server : ' . $request['code']];
            }
            return ['error' => 'Some error PVZ'];
        }

        protected function getPvzDistance($cityCode)
        {
            $request = $this->sendCurlRequest('http://office-ext-integration.production.k8s-local.cdek.ru/api/office/getInRadius?cityCode=' . $cityCode . '&distance=10000');

            if ($request && $request['code'] === 200) {
                $pvz = json_decode($request['result']);

                $arList = [];

                foreach ($pvz as $pvzPoint) {
                    $cityCode = $pvzPoint->cityCode;
                    $code = $pvzPoint->code;

                    $arList[$code] = [
                        'Name' => $pvzPoint->name,
                        'WorkTime' => $pvzPoint->workTime,
                        'Address' => $pvzPoint->address,
                        'Phone' => $pvzPoint->phone,
                        'Note' => $pvzPoint->note,
                        'cX' => $pvzPoint->coordX,
                        'cY' => $pvzPoint->coordY,
                        'Dressing' => ((string)$pvzPoint->isDressingRoom === 'true'),
                        'Cash' => ((string)$pvzPoint->haveCashless === 'true'),
                        'Postamat' => (strtolower((string)$pvzPoint->type) === 'postamat'),
                        'Station' => $pvzPoint->nearestStation,
                        'Site' => (string)$pvzPoint->site,
                        'Metro' => (string)$pvzPoint->metroStation,
                        'AddressComment' => (string)$pvzPoint->addressComment,
                        'CityCode' => (string)$cityCode,
                    ];
                    if ($pvzPoint->weightLimit) {
                        $arList[$code]['WeightLim'] = [
                            'MIN' => (float)$pvzPoint->weightLimit->weightMin,
                            'MAX' => (float)$pvzPoint->weightLimit->weightMax,
                        ];
                    }

                    $arImgs = [];

                    foreach ($pvzPoint->officeImageList as $img) {
                        if (!str_contains($_tmpUrl = (string)$img->url, 'http')) {
                            continue;
                        }
                        $arImgs[] = (string)$img->url;
                    }

                    if (\count($arImgs = array_filter($arImgs))) {
                        $arList[$code]['Picture'] = $arImgs;
                    }
                    $arList[$code]['Path'] = '';
                }

                return $arList;
            }

            if ($request) {
                return ['error' => 'Wrong answer code from server : ' . $request['code']];
            }
            return ['error' => 'Some error PVZ'];
        }
    }

    /** address, city, etc */
    class AddressAction extends BaseAction
    {
        /**
         * @param array $data (optional)
         * @return array|string[]
         */
        public function run($data = [])
        {
            if ($city = $this->controller->getRequestValue(
                'city',
                $this->controller->getValue($data, 'city')
            )
            ) {
                return $this->getCityByName($city);
            }

            if ($address = $this->controller->getRequestValue(
                'address',
                $this->controller->getValue($data, 'address')
            )
            ) {
                return $this->getCityByAddress($address);
            }

            return ['error' => 'No city to search given'];
        }

        public function getCityByAddress($address)
        {
            $arReturn = [];
            $arStages = ['country' => false, 'region' => false, 'subregion' => false];
            $arAddress = explode(',', $address);

            $ind = 0;
            // finging country in address
            if (\in_array((string)$arAddress[0], $this->getCountries(), true)) {
                $arStages['country'] = mb_strtolower(trim($arAddress[0]));
                ++$ind;
            }
            // finding region in address
            foreach ($this->getRegion() as $regionStr) {
                $search = mb_strtolower(trim($arAddress[$ind]));
                $indSearch = strpos($search, $regionStr);
                if ($indSearch !== false) {
                    if ($indSearch) {
                        $arStages['region'] = mb_substr($search, 0, strpos($search, $regionStr));
                    } else {
                        $arStages['region'] = mb_substr($search, mb_strlen($regionStr));
                    }
                    $arStages['region'] = trim($arStages['region']);
                    ++$ind;
                    break;
                }
            }
            // finding subregions
            foreach ($this->getSubRegion() as $subRegionStr) {
                $search = mb_strtolower(trim($arAddress[$ind]));
                $indSearch = strpos($search, $subRegionStr);
                if ($indSearch !== false) {
                    if ($indSearch) {
                        $arStages['subregion'] = mb_substr($search, 0, strpos($search, $subRegionStr));
                    } else {
                        $arStages['subregion'] = mb_substr($search, mb_strlen($subRegionStr));
                    }
                    $arStages['subregion'] = trim($arStages['subregion']);
                    ++$ind;
                    break;
                }
            }
            // finding city
            $cityName = trim($arAddress[$ind]);
            $cdekCity = $this->getCityByName($cityName, false);

            if (!empty($cdekCity['error'])) {
                foreach ($this->getCityDef() as $placeLbl) {
                    $search = str_replace('ё', 'е', mb_strtolower(trim($arAddress[$ind])));
                    $indSearch = strpos($search, $placeLbl);
                    if ($indSearch !== false) {
                        if ($indSearch) {
                            $search = mb_substr($search, 0, strpos($search, $placeLbl));
                        } else {
                            $search = mb_substr($search, mb_strlen($placeLbl));
                        }
                        $search = trim($search);
                        $cityName = $search;
                        $cdekCity = $this->getCityByName($search, false);
                        break;
                    }
                }
            }

            if (!empty($cdekCity['error'])) {
                $arReturn['error'] = $cdekCity['error'];
            } else {
                if (\count($cdekCity['cities']) > 0) {
                    $pretend = false;
                    $arPretend = [];
                    // parseCountry
                    if ($arStages['country']) {
                        foreach ($cdekCity['cities'] as $arCity) {
                            $possCountry = mb_strtolower($arCity['country']);
                            if (!$possCountry || mb_stripos($arStages['country'], $possCountry) !== false) {
                                $arPretend[] = $arCity;
                            }
                        }
                    } else {
                        $arPretend = $cdekCity['cities'];
                    }

                    // parseRegion
                    if (!empty($arStages['region']) && (\count($arPretend) > 1)) {
                        $_arPretend = [];
                        foreach ($arPretend as $arCity) {
                            $possRegion = str_replace($this->getRegion(), '', mb_strtolower(trim($arCity['region'])));
                            $possRegion = str_replace('  ', ' ', $possRegion);
                            if (!$possRegion || mb_stripos($possRegion, str_replace($this->getRegion(), '', $arStages['region'])) !== false) {
                                $_arPretend[] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }

                    // parseSubRegion
                    if (!empty($arStages['subregion']) && (\count($arPretend) > 1)) {
                        $_arPretend = [];
                        foreach ($arPretend as $arCity) {
                            $possSubRegion = mb_strtolower($arCity['city']);
                            if (!$possSubRegion || mb_stripos($possSubRegion, $arStages['subregion']) !== false) {
                                $_arPretend[] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }
                    // parseUndefined
                    // not full city name
                    if (\count($arPretend) > 1) {
                        $_arPretend = [];
                        foreach ($arPretend as $arCity) {
                            if (mb_stripos($arCity['city'], ',') === false) {
                                $_arPretend[] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }
                    if (\count($arPretend) > 1) {
                        $_arPretend = [];
                        foreach ($arPretend as $arCity) {
                            if (mb_strlen($arCity['city']) === mb_strlen($cityName)) {
                                $_arPretend[] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }
                    // federalCities
                    if (\count($arPretend) > 1) {
                        $_arPretend = [];
                        foreach ($arPretend as $arCity) {
                            if ($arCity['city'] === $arCity['region']) {
                                $_arPretend[] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }

                    // end
                    if (\count($arPretend) === 1) {
                        $pretend = array_pop($arPretend);
                    }
                } else {
                    $pretend = $cdekCity['cities'][0];
                }
                if ($pretend) {
                    $arReturn['city'] = $pretend;
                } else {
                    $arReturn['error'] = 'Undefined city';
                }
            }
            return $arReturn;
        }

        /**
         * @param string $name
         * @param bool $single
         * @return array|string[]
         */
        protected function getCityByName($name, $single = true)
        {
            $arReturn = [];

            // При получении полного списка населенных пунктов, название НП Быков записано как "рабочий посёлок Быково"
            // Для получения данных по этому НП в апи нужно название "Быково село"
            if ($name === 'рабочий посёлок Быково') {
                $name = 'Быково село';
            }

            // При получении полного списка населенных пунктов, название НП Воскресенск записано как "Воскресенск"
            // Для получения данных по этому НП в апи нужно название "Воскресенск, Московская область"
            if ($name === 'Воскресенск') {
                $name = 'Воскресенск, Московская область';
            }

            $result = $this->sendCurlRequest(
                'http://api.cdek.ru/city/getListByTerm/json.php?q=' . urlencode($name)
            );
            if ($result && $result['code'] === 200) {
                $result = json_decode($result['result']);
                if (!isset($result->geonames)) {
                    $arReturn = ['error' => 'No cities found'];
                } else {
                    if ($single) {
                        $arReturn = [
                            'id' => $result->geonames[0]->id,
                            'city' => $result->geonames[0]->cityName,
                            'region' => $result->geonames[0]->regionName,
                            'country' => $result->geonames[0]->countryName,
                        ];
                    } else {
                        $arReturn['cities'] = [];
                        foreach ($result->geonames as $city) {
                            $arReturn['cities'][] = [
                                'id' => $city->id,
                                'city' => $city->cityName,
                                'region' => $city->regionName,
                                'country' => $city->countryName,
                            ];
                        }
                    }
                }
            } else {
                $arReturn = ['error' => 'Wrong answer code from server : ' . $result['code']];
            }

            return $arReturn;
        }

        protected function getCountries()
        {
            return ['Россия', 'Беларусь', 'Армения', 'Казахстан', 'Киргизия', 'Молдова', 'Таджикистан', 'Узбекистан'];
        }

        protected function getRegion()
        {
            return ['автономная область', 'область', 'республика', 'респ.', 'автономный округ', 'округ', 'край', 'обл.'];
        }

        protected function getSubRegion()
        {
            return ['муниципальный район', 'район', 'городской округ'];
        }

        protected function getCityDef()
        {
            return [
                'поселок городского типа',
                'населенный пункт',
                'курортный поселок',
                'дачный поселок',
                'рабочий поселок',
                'почтовое отделение',
                'сельское поселение',
                'ж/д станция',
                'станция',
                'городок',
                'деревня',
                'микрорайон',
                'станица',
                'хутор',
                'аул',
                'поселок',
                'село',
                'снт',
            ];
        }
    }

    /** calc delivery */
    class CalculationAction extends BaseAction
    {
        /**
         * @return array|string[]
         */
        public function run()
        {
            $shipment = $this->controller->getRequestValue('shipment', []);

            if (empty($shipment['tariffList'])) {
                $shipment['tariffList'] = $this->controller->getSettings()->getTariffPriority($shipment['type']);
            }

            if (($ref = $this->controller->getValue($_SERVER, 'HTTP_REFERER')) && !empty($ref)) {
                $shipment['ref'] = $ref;
            }

            if (empty($shipment['cityToId'])) {
                $cityTo = $this->sendToCity($shipment['cityTo']);
                if ($cityTo && $cityTo['code'] === 200) {
                    $pretendents = json_decode($cityTo['result']);
                    if ($pretendents && isset($pretendents->geonames)) {
                        $shipment['cityToId'] = $pretendents->geonames[0]->id;
                    }
                }
            }

            if ($shipment['cityToId']) {
                $answer = $this->calculate($shipment);

                if ($answer) {
                    $returnData = [
                        'result' => $answer,
                        'type' => $shipment['type'],
                    ];
                    if ($shipment['timestamp']) {
                        $returnData['timestamp'] = $shipment['timestamp'];
                    }

                    return $returnData;
                }
            }

            return ['error' => 'City to not found'];
        }

        protected function calculate($shipment)
        {
            if (empty($shipment['goods'])) {
                return ['error' => 'The dimensions of the goods are not defined'];
            }

            $headers = $this->getHeaders();

            $arData = [
                'dateExecute' => $this->controller->getValue($headers, 'date'),
                'version' => '1.0',
                'authLogin' => $this->controller->getValue($headers, 'account'),
                'secure' => $this->controller->getValue($headers, 'secure'),
                'senderCityId' => $this->controller->getValue($shipment, 'cityFromId'),
                'receiverCityId' => $this->controller->getValue($shipment, 'cityToId'),
                'ref' => $this->controller->getValue($shipment, 'ref'),
                'widget' => 1,
                'currency' => $this->controller->getValue($shipment, 'currency', 'RUB'),
            ];

            if (!empty($shipment['tariffList'])) {
                foreach ($shipment['tariffList'] as $priority => $tariffId) {
                    $tariffId = (int)$tariffId;
                    $arData['tariffList'][] = [
                        'priority' => $priority + 1,
                        'id' => $tariffId,
                    ];
                }
            }

            $arData['goods'] = [];
            foreach ($shipment['goods'] as $arGood) {
                $arData['goods'][] = [
                    'weight' => $arGood['weight'],
                    'length' => $arGood['length'],
                    'width' => $arGood['width'],
                    'height' => $arGood['height'],
                ];
            }

            $type = $this->controller->getValue($shipment, 'type');

            $resultTariffs = $this->sendCurlRequest(
                'http://api.cdek.ru/calculator/calculate_tarifflist.php',
                json_encode($arData),
                true
            );
            if ($resultTariffs && $resultTariffs['code'] === 200) {
                if (null !== json_decode($resultTariffs['result'], false)) {
                    $resultTariffs = json_decode($resultTariffs['result'], true);

                    $returnFirst = static function ($array) {
                        $first = reset($array);

                        return $first['result'];
                    };

                    if (!empty($type) && empty($arData['tariffId'])) {
                        $tariffListSorted = $this->controller->getSettings()->getTariffPriority($type);

                        $array_column = static fn ($array, $columnName) => array_map(static fn ($element) => $element[$columnName], $array);

                        $calcTariffs = array_filter(
                            $this->controller->getValue($resultTariffs, 'result', []),
                            static fn ($item) => $item['status'] === true
                        ) ?: [];

                        $calcTariffs = array_combine($array_column($calcTariffs, 'tariffId'), $calcTariffs);

                        foreach ($tariffListSorted as $tariffId) {
                            if (\array_key_exists($tariffId, $calcTariffs)) {
                                return $calcTariffs[$tariffId]['result'];
                            }
                        }
                        return $returnFirst($calcTariffs);
                    }
                    return $returnFirst($resultTariffs);
                }
                return ['error' => 'Wrong server answer'];
            }

            return ['error' => 'Wrong answer code from server : ' . $resultTariffs['code']];
        }

        protected function sendToCity($city)
        {
            static $action;
            if (!$action) {
                $action = new AddressAction($this->controller);
            }

            return $action->run(['city' => $city]);
        }

        protected function getHeaders()
        {
            $date = date('Y-m-d');
            $headers = [
                'date' => $date,
            ];

            $settings = $this->controller->getSettings();
            if ($settings->hasCredentials()) {
                $headers = [
                    'date' => $date,
                    'account' => $settings->getAccount(),
                    'secure' => md5($date . '&' . $settings->getKey()),
                ];
            }

            return $headers;
        }
    }

    /** translate */
    class I18nAction extends BaseAction
    {
        /**
         * @return array with translations
         */
        public function run()
        {
            return ['LANG' => $this->controller->getValue(
                $translate = [
                    'rus' => [
                        'YOURCITY' => 'Ваш город',
                        'COURIER' => 'Курьер',
                        'PICKUP' => 'Самовывоз',
                        'TERM' => 'Срок',
                        'PRICE' => 'Стоимость',
                        'DAY' => 'дн.',
                        'RUB' => ' руб.',
                        'KZT' => 'KZT',
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                        'CNY' => 'CNY',
                        'BYN' => 'BYN',
                        'UAH' => 'UAH',
                        'KGS' => 'KGS',
                        'AMD' => 'AMD',
                        'TRY' => 'TRY',
                        'THB' => 'THB',
                        'KRW' => 'KRW',
                        'AED' => 'AED',
                        'UZS' => 'UZS',
                        'MNT' => 'MNT',
                        'NODELIV' => 'Нет доставки',
                        'CITYSEARCH' => 'Поиск города',
                        'ALL' => 'Все',
                        'PVZ' => 'Пункты выдачи',
                        'POSTOMAT' => 'Постаматы',
                        'MOSCOW' => 'Москва',
                        'RUSSIA' => 'Россия',
                        'COUNTING' => 'Идет расчет',

                        'NO_AVAIL' => 'Нет доступных способов доставки',
                        'CHOOSE_TYPE_AVAIL' => 'Выберите способ доставки',
                        'CHOOSE_OTHER_CITY' => 'Выберите другой населенный пункт',

                        'TYPE_ADDRESS' => 'Уточните адрес',
                        'TYPE_ADDRESS_HERE' => 'Введите адрес доставки',

                        'L_ADDRESS' => 'Адрес пункта выдачи заказов',
                        'L_TIME' => 'Время работы',
                        'L_WAY' => 'Как к нам проехать',
                        'L_CHOOSE' => 'Выбрать',

                        'H_LIST' => 'Список пунктов выдачи заказов',
                        'H_PROFILE' => 'Способ доставки',
                        'H_CASH' => 'Расчет картой',
                        'H_DRESS' => 'С примеркой',
                        'H_POSTAMAT' => 'Постаматы СДЭК',
                        'H_SUPPORT' => 'Служба поддержки',
                        'H_QUESTIONS' => 'Если у вас есть вопросы, можете<br> задать их нашим специалистам',
                        'ADDRESS_WRONG' => 'Невозможно определить выбранное местоположение. Уточните адрес из выпадающего списка в адресной строке.',
                        'ADDRESS_ANOTHER' => 'Ознакомьтесь с новыми условиями доставки для выбранного местоположения.',
                    ],
                    'eng' => [
                        'YOURCITY' => 'Your city',
                        'COURIER' => 'Courier',
                        'PICKUP' => 'Pickup',
                        'TERM' => 'Term',
                        'PRICE' => 'Price',
                        'DAY' => 'days',
                        'RUB' => 'RUB',
                        'KZT' => 'KZT',
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                        'CNY' => 'CNY',
                        'BYN' => 'BYN',
                        'UAH' => 'UAH',
                        'KGS' => 'KGS',
                        'AMD' => 'AMD',
                        'TRY' => 'TRY',
                        'THB' => 'THB',
                        'KRW' => 'KRW',
                        'AED' => 'AED',
                        'UZS' => 'UZS',
                        'MNT' => 'MNT',
                        'NODELIV' => 'Not delivery',
                        'CITYSEARCH' => 'Search for a city',
                        'ALL' => 'All',
                        'PVZ' => 'Points of self-delivery',
                        'POSTOMAT' => 'Postamats',
                        'MOSCOW' => 'Moscow',
                        'RUSSIA' => 'Russia',
                        'COUNTING' => 'Calculation',

                        'NO_AVAIL' => 'No shipping methods available',
                        'CHOOSE_TYPE_AVAIL' => 'Choose a shipping method',
                        'CHOOSE_OTHER_CITY' => 'Choose another location',

                        'TYPE_ADDRESS' => 'Specify the address',
                        'TYPE_ADDRESS_HERE' => 'Enter the delivery address',

                        'L_ADDRESS' => 'Adress of self-delivery',
                        'L_TIME' => 'Working hours',
                        'L_WAY' => 'How to get to us',
                        'L_CHOOSE' => 'Choose',

                        'H_LIST' => 'List of self-delivery',
                        'H_PROFILE' => 'Shipping method',
                        'H_CASH' => 'Payment by card',
                        'H_DRESS' => 'Dressing room',
                        'H_POSTAMAT' => 'Postamats CDEK',
                        'H_SUPPORT' => 'Support',
                        'H_QUESTIONS' => 'If you have any questions,<br> you can ask them to our specialists',

                        'ADDRESS_WRONG' => 'Impossible to define address. Please, recheck the address.',
                        'ADDRESS_ANOTHER' => 'Read the new terms and conditions.',
                    ],
                ],
                $this->controller->getRequestValue('lang', 'rus'),
                $translate['rus']
            )];
        }
    }

    /** all other actions */
    class UnknownAction extends BaseAction
    {
        /**
         * @return string
         */
        public function run()
        {
            return 'unknownAction';
        }
    }

    class Controller
    {
        /**
         * @var array
         */
        private $request;
        /**
         * @var array
         */
        private $response;
        /**
         * @var Settings
         */
        private $settings;

        /**
         * Controller constructor.
         */
        protected function __construct(Settings $settings)
        {
            $this->request = $this->getRequest();
            $this->response = [];
            $this->settings = $settings;
        }

        /**
         * Entrypoint.
         */
        public static function processRequest(Settings $settings): void
        {
            $self = new self($settings);
            $self->toResponse(
                $self->getAction()
                    ->run()
            );
            echo json_encode($self->response ?: false);
        }

        /**
         * @param array|mixed $data
         */
        public function toResponse($data): void
        {
            if (!\is_array($data)) {
                $data = ['info' => $data];
            }

            foreach ($data as $key => $value) {
                if ($key === 'error') {
                    if (!\array_key_exists($key, $this->response)) {
                        $this->response[$key] = [];
                    }
                    $this->response[$key][] = $value;
                } else {
                    $this->response[$key] = $value;
                }
            }
        }

        /**
         * @param array $fromArray
         * @param int|string $key
         * @param mixed|null $default
         * @return mixed|null
         */
        public function getValue($fromArray, $key, $default = null)
        {
            return $fromArray[$key] ?? $default;
        }

        /**
         * @param int|string $key
         * @param mixed|null $default
         * @return mixed|null
         */
        public function getRequestValue($key, $default = null)
        {
            return $this->getValue($this->request, $key, $default);
        }

        /**
         * @return Settings
         */
        public function getSettings()
        {
            return $this->settings;
        }

        /**
         * @return BaseAction concrete action implementation
         */
        protected function getAction()
        {
            $actionName = $this->getRequestValue('isdek_action');
            switch (true) {
                case $actionName === 'getPVZ':
                    $action = new PickupAction($this);
                    break;
                case $actionName === 'getCity':
                    $action = new AddressAction($this);
                    break;
                case $actionName === 'calc':
                    $action = new CalculationAction($this);
                    break;
                case $actionName === 'getLang':
                    $action = new I18nAction($this);
                    break;
                default:
                    $action = new UnknownAction($this);
            }
            return $action;
        }

        /**
         * @return array - one of $_REQUEST, $_POST, $_GET
         */
        protected function getRequest()
        {
            $request = $_REQUEST;
            if (isset($_SERVER['REQUEST_METHOD']) && \in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) {
                $request = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
            }
            return $request;
        }
    }
}
