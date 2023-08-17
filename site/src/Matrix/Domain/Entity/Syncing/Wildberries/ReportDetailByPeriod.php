<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Syncing\Wildberries;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

class ReportDetailByPeriod
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $realizationreport_id; // ": 1234567,
    private ?DateTimeImmutable $date_from; // : "2022-10-17T00:00:00Z",
    private ?DateTimeImmutable $date_to; // ": "2022-10-23T00:00:00Z",
    private ?DateTimeImmutable $create_dt;  // ": "2022-10-24T14:40:32",
    private ?string $currency_name; // : "руб",
    private ?string $suppliercontract_code; // ": null,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $rrd_id = null; // ": 1232610467,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $gi_id = null; // ": 123456,
    private ?string $subject_name; // ": "Мини-печи",

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nm_id = null; // ": 1234567,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $brand_name = null; // ": "BlahBlah",
    private string $sa_name; // ": "MAB123",
    private string $ts_name; // ": "0",
    private string $barcode; // ": "1231312352310",
    private string $doc_type_name; // ": "Продажа",

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $quantity = null; // ": 1,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $retail_price = null; // ": 1249,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $retail_amount = null; // ": 367,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $sale_percent = null; // ": 68,

    private float $commission_percent; // ": 0.1324,
    private string $office_name; // ": "Коледино",
    private string $supplier_oper_name; // ": "Продажа",
    private DateTimeImmutable $order_dt; // ": "2022-10-13T00:00:00Z",
    private DateTimeImmutable $sale_dt; // ": "2022-10-20T00:00:00Z",
    private DateTimeImmutable $rr_dt; // ": "2022-10-20T00:00:00Z",

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $shk_id = null; // ": 1239159661,
    private float $retail_price_withdisc_rub; // ": 399.68,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $delivery_amount = null; // ": 0,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $return_amount = null; // ": 0,
    private float $delivery_rub; // ": 0,
    private string $gi_box_type_name; // ": "Монопалета",
    private float $product_discount_for_report; // ": 399.68,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $supplier_promo = null; // ": 0,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $rid = null; // ": 123722249253,
    private float $ppvz_spp_prc; // ": 0.1581,
    private float $ppvz_kvw_prc_base; // ": 0.15,
    private float $ppvz_kvw_prc; // ": -0.0081,
    private float $sup_rating_prc_up; // ": 0,
    private bool $is_kgvp_v2; // ": 0,
    private float $ppvz_sales_commission; // ": -3.74,
    private float $ppvz_for_pay; // ": 376.99,
    private float $ppvz_reward; // ": 0,
    private float $acquiring_fee; // ": 14.89,
    private string $acquiring_bank; // ": "Тинькофф",
    private float $ppvz_vw; // ": -3.74,
    private float $ppvz_vw_nds; // ": -0.75,

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $ppvz_office_id = null; // ": 105383,
    private string $ppvz_office_name; // ": "Пункт самовывоза (ПВЗ)",

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $ppvz_supplier_id = null; // ": 186465,
    private string $ppvz_supplier_name; // ": "ИП Жасмин",
    private string $ppvz_inn; // ": "010101010101",
    private string $declaration_number; // ": "",
    private string $bonus_type_name; // ": "Штраф МП. Невыполненный заказ (отмена клиентом после недовоза)",
    private string $sticker_id; // ": "1964038895",
    private string $site_country; // ": "RU",
    private float $penalty; // ": 231.35,
    private float $additional_payment; // ": 0,
    private float $rebill_logistic_cost; // ": 1.349,
    private string $rebill_logistic_org; // ": "ИП Иванов Иван Иванович(123456789012)",
    private string $kiz; // ": "0102900000376311210G2CIS?ehge)S\u001d91002A\u001d92F9Qof4FDo/31Icm14kmtuVYQzLypxm3HWkC1vQ/+pVVjm1dNAth1laFMoAGn7yEMWlTjxIe7lQnJqZ7TRZhlHQ==",
    private string $srid;

    public function getRealizationreportId(): int
    {
        return $this->realizationreport_id;
    }

    public function setRealizationreportId(int $realizationreport_id): void
    {
        $this->realizationreport_id = $realizationreport_id;
    }

    public function getDateFrom(): DateTimeImmutable
    {
        return $this->date_from;
    }

    public function setDateFrom(DateTimeImmutable $date_from): void
    {
        $this->date_from = $date_from;
    }

    public function getDateTo(): DateTimeImmutable
    {
        return $this->date_to;
    }

    public function setDateTo(DateTimeImmutable $date_to): void
    {
        $this->date_to = $date_to;
    }

    public function getCreateDt(): DateTimeImmutable
    {
        return $this->create_dt;
    }

    public function setCreateDt(DateTimeImmutable $create_dt): void
    {
        $this->create_dt = $create_dt;
    }

    public function getCurrencyName(): string
    {
        return $this->currency_name;
    }

    public function setCurrencyName(string $currency_name): void
    {
        $this->currency_name = $currency_name;
    }

    public function getSuppliercontractCode(): string
    {
        return $this->suppliercontract_code;
    }

    public function setSuppliercontractCode(string $suppliercontract_code): void
    {
        $this->suppliercontract_code = $suppliercontract_code;
    }

    public function getRrdId(): int
    {
        return $this->rrd_id;
    }

    public function setRrdId(int $rrd_id): void
    {
        $this->rrd_id = $rrd_id;
    }

    public function getGiId(): int
    {
        return $this->gi_id;
    }

    public function setGiId(int $gi_id): void
    {
        $this->gi_id = $gi_id;
    }

    public function getSubjectName(): string
    {
        return $this->subject_name;
    }

    public function setSubjectName(string $subject_name): void
    {
        $this->subject_name = $subject_name;
    }

    public function getNmId(): int
    {
        return $this->nm_id;
    }

    public function setNmId(int $nm_id): void
    {
        $this->nm_id = $nm_id;
    }

    public function getBrandName(): int
    {
        return $this->brand_name;
    }

    public function setBrandName(int $brand_name): void
    {
        $this->brand_name = $brand_name;
    }

    public function getSaName(): string
    {
        return $this->sa_name;
    }

    public function setSaName(string $sa_name): void
    {
        $this->sa_name = $sa_name;
    }

    public function getTsName(): string
    {
        return $this->ts_name;
    }

    public function setTsName(string $ts_name): void
    {
        $this->ts_name = $ts_name;
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getDocTypeName(): string
    {
        return $this->doc_type_name;
    }

    public function setDocTypeName(string $doc_type_name): void
    {
        $this->doc_type_name = $doc_type_name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getRetailPrice(): int
    {
        return $this->retail_price;
    }

    public function setRetailPrice(int $retail_price): void
    {
        $this->retail_price = $retail_price;
    }

    public function getRetailAmount(): int
    {
        return $this->retail_amount;
    }

    public function setRetailAmount(int $retail_amount): void
    {
        $this->retail_amount = $retail_amount;
    }

    public function getSalePercent(): int
    {
        return $this->sale_percent;
    }

    public function setSalePercent(int $sale_percent): void
    {
        $this->sale_percent = $sale_percent;
    }

    public function getCommissionPercent(): float
    {
        return $this->commission_percent;
    }

    public function setCommissionPercent(float $commission_percent): void
    {
        $this->commission_percent = $commission_percent;
    }

    public function getOfficeName(): string
    {
        return $this->office_name;
    }

    public function setOfficeName(string $office_name): void
    {
        $this->office_name = $office_name;
    }

    public function getSupplierOperName(): string
    {
        return $this->supplier_oper_name;
    }

    public function setSupplierOperName(string $supplier_oper_name): void
    {
        $this->supplier_oper_name = $supplier_oper_name;
    }

    public function getOrderDt(): DateTimeImmutable
    {
        return $this->order_dt;
    }

    public function setOrderDt(DateTimeImmutable $order_dt): void
    {
        $this->order_dt = $order_dt;
    }

    public function getSaleDt(): DateTimeImmutable
    {
        return $this->sale_dt;
    }

    public function setSaleDt(DateTimeImmutable $sale_dt): void
    {
        $this->sale_dt = $sale_dt;
    }

    public function getRrDt(): DateTimeImmutable
    {
        return $this->rr_dt;
    }

    public function setRrDt(DateTimeImmutable $rr_dt): void
    {
        $this->rr_dt = $rr_dt;
    }

    public function getShkId(): int
    {
        return $this->shk_id;
    }

    public function setShkId(int $shk_id): void
    {
        $this->shk_id = $shk_id;
    }

    public function getRetailPriceWithdiscRub(): float
    {
        return $this->retail_price_withdisc_rub;
    }

    public function setRetailPriceWithdiscRub(float $retail_price_withdisc_rub): void
    {
        $this->retail_price_withdisc_rub = $retail_price_withdisc_rub;
    }

    public function getDeliveryAmount(): int
    {
        return $this->delivery_amount;
    }

    public function setDeliveryAmount(int $delivery_amount): void
    {
        $this->delivery_amount = $delivery_amount;
    }

    public function getReturnAmount(): int
    {
        return $this->return_amount;
    }

    public function setReturnAmount(int $return_amount): void
    {
        $this->return_amount = $return_amount;
    }

    public function getDeliveryRub(): float
    {
        return $this->delivery_rub;
    }

    public function setDeliveryRub(float $delivery_rub): void
    {
        $this->delivery_rub = $delivery_rub;
    }

    public function getGiBoxTypeName(): string
    {
        return $this->gi_box_type_name;
    }

    public function setGiBoxTypeName(string $gi_box_type_name): void
    {
        $this->gi_box_type_name = $gi_box_type_name;
    }

    public function getProductDiscountForReport(): float
    {
        return $this->product_discount_for_report;
    }

    public function setProductDiscountForReport(float $product_discount_for_report): void
    {
        $this->product_discount_for_report = $product_discount_for_report;
    }

    public function getSupplierPromo(): int
    {
        return $this->supplier_promo;
    }

    public function setSupplierPromo(int $supplier_promo): void
    {
        $this->supplier_promo = $supplier_promo;
    }

    public function getRid(): int
    {
        return $this->rid;
    }

    public function setRid(int $rid): void
    {
        $this->rid = $rid;
    }

    public function getPpvzSppPrc(): float
    {
        return $this->ppvz_spp_prc;
    }

    public function setPpvzSppPrc(float $ppvz_spp_prc): void
    {
        $this->ppvz_spp_prc = $ppvz_spp_prc;
    }

    public function getPpvzKvwPrcBase(): float
    {
        return $this->ppvz_kvw_prc_base;
    }

    public function setPpvzKvwPrcBase(float $ppvz_kvw_prc_base): void
    {
        $this->ppvz_kvw_prc_base = $ppvz_kvw_prc_base;
    }

    public function getPpvzKvwPrc(): float
    {
        return $this->ppvz_kvw_prc;
    }

    public function setPpvzKvwPrc(float $ppvz_kvw_prc): void
    {
        $this->ppvz_kvw_prc = $ppvz_kvw_prc;
    }

    public function getSupRatingPrcUp(): float
    {
        return $this->sup_rating_prc_up;
    }

    public function setSupRatingPrcUp(float $sup_rating_prc_up): void
    {
        $this->sup_rating_prc_up = $sup_rating_prc_up;
    }

    public function isIsKgvpV2(): bool
    {
        return $this->is_kgvp_v2;
    }

    public function setIsKgvpV2(bool $is_kgvp_v2): void
    {
        $this->is_kgvp_v2 = $is_kgvp_v2;
    }

    public function getPpvzSalesCommission(): float
    {
        return $this->ppvz_sales_commission;
    }

    public function setPpvzSalesCommission(float $ppvz_sales_commission): void
    {
        $this->ppvz_sales_commission = $ppvz_sales_commission;
    }

    public function getPpvzForPay(): float
    {
        return $this->ppvz_for_pay;
    }

    public function setPpvzForPay(float $ppvz_for_pay): void
    {
        $this->ppvz_for_pay = $ppvz_for_pay;
    }

    public function getPpvzReward(): float
    {
        return $this->ppvz_reward;
    }

    public function setPpvzReward(float $ppvz_reward): void
    {
        $this->ppvz_reward = $ppvz_reward;
    }

    public function getAcquiringFee(): float
    {
        return $this->acquiring_fee;
    }

    public function setAcquiringFee(float $acquiring_fee): void
    {
        $this->acquiring_fee = $acquiring_fee;
    }

    public function getAcquiringBank(): string
    {
        return $this->acquiring_bank;
    }

    public function setAcquiringBank(string $acquiring_bank): void
    {
        $this->acquiring_bank = $acquiring_bank;
    }

    public function getPpvzVw(): float
    {
        return $this->ppvz_vw;
    }

    public function setPpvzVw(float $ppvz_vw): void
    {
        $this->ppvz_vw = $ppvz_vw;
    }

    public function getPpvzVwNds(): float
    {
        return $this->ppvz_vw_nds;
    }

    public function setPpvzVwNds(float $ppvz_vw_nds): void
    {
        $this->ppvz_vw_nds = $ppvz_vw_nds;
    }

    public function getPpvzOfficeId(): int
    {
        return $this->ppvz_office_id;
    }

    public function setPpvzOfficeId(int $ppvz_office_id): void
    {
        $this->ppvz_office_id = $ppvz_office_id;
    }

    public function getPpvzOfficeName(): string
    {
        return $this->ppvz_office_name;
    }

    public function setPpvzOfficeName(string $ppvz_office_name): void
    {
        $this->ppvz_office_name = $ppvz_office_name;
    }

    public function getPpvzSupplierId(): int
    {
        return $this->ppvz_supplier_id;
    }

    public function setPpvzSupplierId(int $ppvz_supplier_id): void
    {
        $this->ppvz_supplier_id = $ppvz_supplier_id;
    }

    public function getPpvzSupplierName(): string
    {
        return $this->ppvz_supplier_name;
    }

    public function setPpvzSupplierName(string $ppvz_supplier_name): void
    {
        $this->ppvz_supplier_name = $ppvz_supplier_name;
    }

    public function getPpvzInn(): string
    {
        return $this->ppvz_inn;
    }

    public function setPpvzInn(string $ppvz_inn): void
    {
        $this->ppvz_inn = $ppvz_inn;
    }

    public function getDeclarationNumber(): string
    {
        return $this->declaration_number;
    }

    public function setDeclarationNumber(string $declaration_number): void
    {
        $this->declaration_number = $declaration_number;
    }

    public function getBonusTypeName(): string
    {
        return $this->bonus_type_name;
    }

    public function setBonusTypeName(string $bonus_type_name): void
    {
        $this->bonus_type_name = $bonus_type_name;
    }

    public function getStickerId(): string
    {
        return $this->sticker_id;
    }

    public function setStickerId(string $sticker_id): void
    {
        $this->sticker_id = $sticker_id;
    }

    public function getSiteCountry(): string
    {
        return $this->site_country;
    }

    public function setSiteCountry(string $site_country): void
    {
        $this->site_country = $site_country;
    }

    public function getPenalty(): float
    {
        return $this->penalty;
    }

    public function setPenalty(float $penalty): void
    {
        $this->penalty = $penalty;
    }

    public function getAdditionalPayment(): float
    {
        return $this->additional_payment;
    }

    public function setAdditionalPayment(float $additional_payment): void
    {
        $this->additional_payment = $additional_payment;
    }

    public function getRebillLogisticCost(): float
    {
        return $this->rebill_logistic_cost;
    }

    public function setRebillLogisticCost(float $rebill_logistic_cost): void
    {
        $this->rebill_logistic_cost = $rebill_logistic_cost;
    }

    public function getRebillLogisticOrg(): string
    {
        return $this->rebill_logistic_org;
    }

    public function setRebillLogisticOrg(string $rebill_logistic_org): void
    {
        $this->rebill_logistic_org = $rebill_logistic_org;
    }

    public function getKiz(): string
    {
        return $this->kiz;
    }

    public function setKiz(string $kiz): void
    {
        $this->kiz = $kiz;
    }

    public function getSrid(): string
    {
        return $this->srid;
    }

    public function setSrid(string $srid): void
    {
        $this->srid = $srid;
    } // ": "711ab8cb94e040f98b3804e5f331bf7a_r"
}
