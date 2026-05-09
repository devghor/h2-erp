# Module Spec: Products

**Status:** Planned
**Module Key:** `product`

---

## Overview
The Products Module is the core inventory and catalog management component of the system. It manages all product-related information including product definitions, pricing, taxation, variants, warranty details, barcode configuration, stock preparation, and sales-related settings.

This module supports multiple product types such as Standard, Combo, Digital, and Service products, enabling businesses to handle both physical and non-physical items within a unified structure.

---

## Entities

### product_base_products
- name
- type {Standard, Combo, Digital, Service}
- code
- barcode_symbology {EAN-8, EAN-13, UPC-A, UPC-E, CODE-39, CODE-128, ITF, CODABAR, QR-CODE, PDF417, DATA-MATRIX}
- product_brand_id
- product_category_id
- product_unit_id
- product_sale_unit_id
- product_puchase_unit_id
- product_cost
- profit_margin_type {Percentage, Flat}
- profit_margin
- product_price
- wholesale_price
- daily_sale_objective
- product_tax {CGST@10,SGST@8,VAT@15%,GST@15,VAT}
- tax_method {Exclusive, Inclusive}
- warnaty_value
- waranty_duration_type {Day, Month, Year}
- guarantee_value
- guarantee_duration_type{Day,Month,Year}
- is_featured
- is_featured
- embedded_barcode
- product_image
- product_details
- has_batch_and_expire_date
- has_imei_or_serial_no
- has_promotional_price

### product_variant_products
- product_id
- wastage_percent
- quantitiy
- unit_cost
- unit_price
- sub_total
