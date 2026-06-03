# Module Spec: Products

**Status:** Planned
**Module Key:** `product`

---

## Overview

The Products Module is the core catalog and inventory definition layer of the system. It manages product identity, pricing, taxation, barcode configuration, warranty/guarantee terms, and sales settings for every sellable item in the business.

### Product Types

| Type | Description |
|---|---|
| **Standard** | Physical unit with stock tracking. No child items. |
| **Combo** | A bundle composed of other existing products. The only type that has child items in `product_product_items`. |
| **Digital** | Downloadable or non-physical product. No stock, no child items. |
| **Service** | Time or labor-based offering. No stock, no child items. |

**Combo-only rule:** `product_product_items` rows exist only for products whose `type = Combo`. Standard, Digital, and Service products never have item rows.

---

## Entities

### product_products
- name
- type {Standard, Combo, Digital, Service}
  - *Selecting Combo unlocks the Combo Items section (see Behaviour below)*
- code
- barcode_symbology {EAN-8, EAN-13, UPC-A, UPC-E, CODE-39, CODE-128, ITF, CODABAR, QR-CODE, PDF417, DATA-MATRIX}
- product_brand_id
- product_category_id
- product_unit_id
- product_sale_unit_id
- product_purchase_unit_id
- product_cost
- profit_margin_type {Percentage, Flat}
- profit_margin
- product_price
- wholesale_price
- daily_sale_objective
- product_tax {CGST@10, SGST@8, VAT@15%, GST@15, VAT}
- tax_method {Exclusive, Inclusive}
- warranty_value
- warranty_duration_type {Day, Month, Year}
- guarantee_value
- guarantee_duration_type {Day, Month, Year}
- is_featured
- embedded_barcode
- product_image
- product_details
- has_batch_and_expire_date
- has_imei_or_serial_no
- has_promotional_price

### product_product_items
> **Combo-only** — rows exist only when the parent product's `type = Combo`.

- product_id *(parent combo product)*
- item_product_id *(component product selected from existing products)*
- quantity
- unit_cost *(auto-populated from the component product's `product_cost`)*
- unit_price
- wastage_percent
- sub_total *(unit_price × quantity)*

---

## Behaviour / Rules

- Only products with `type = Combo` have entries in `product_product_items`. All other types leave this table empty.
- When the user selects **Combo** as the product type, a **Combo Items** panel appears below the main form with a searchable list of existing products.
- The user checks products to include as components; unchecking removes them from the combo.
- Each included product row shows editable fields: `quantity`, `unit_cost` (pre-filled from component's cost), `unit_price`, and `wastage_percent`. `sub_total` is computed automatically.
- Switching type away from Combo hides the Combo Items panel; on save, any existing item rows for that product are cleared.
