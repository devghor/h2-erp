import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import { X } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.productProducts, { title: 'Add Product', href: '#' }];

type Option = { id: number; name: string };
type UnitOption = { id: number; name: string; code: string };
type EnumOption = { value: string; label: string };
type ProductOption = { id: number; name: string; product_cost: string | null };

interface ComboItem {
    item_product_id: number;
    item_product_name: string;
    quantity: string;
    unit_cost: string;
    unit_price: string;
    wastage_percent: string;
}

interface Props {
    categories: Option[];
    brands: Option[];
    units: UnitOption[];
    allProducts: ProductOption[];
    productTypes: EnumOption[];
    barcodeSymbologies: EnumOption[];
    productTaxes: EnumOption[];
    taxMethods: EnumOption[];
    profitMarginTypes: EnumOption[];
    durationTypes: EnumOption[];
}

const emptyForm = {
    name: '',
    type: '',
    code: '',
    barcode_symbology: '',
    product_brand_id: '',
    product_category_id: '',
    product_unit_id: '',
    product_sale_unit_id: '',
    product_purchase_unit_id: '',
    product_cost: '',
    profit_margin_type: '',
    profit_margin: '',
    product_price: '',
    wholesale_price: '',
    daily_sale_objective: '',
    product_tax: '',
    tax_method: '',
    warranty_value: '',
    warranty_duration_type: '',
    guarantee_value: '',
    guarantee_duration_type: '',
    is_featured: false,
    has_batch_and_expire_date: false,
    has_imei_or_serial_no: false,
    has_promotional_price: false,
    embedded_barcode: '',
    product_details: '',
    product_image: null as File | null,
};

export default function Create({
    categories,
    brands,
    units,
    allProducts,
    productTypes,
    barcodeSymbologies,
    productTaxes,
    taxMethods,
    profitMarginTypes,
    durationTypes,
}: Props) {
    const [form, setForm] = useState({ ...emptyForm });
    const [comboItems, setComboItems] = useState<ComboItem[]>([]);
    const [comboSearch, setComboSearch] = useState('');
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const target = e.target as HTMLInputElement;
        const { name, value, type, files, checked } = target;
        if (type === 'file') {
            setForm((prev) => ({ ...prev, [name]: files?.[0] ?? null }));
        } else if (type === 'checkbox') {
            setForm((prev) => ({ ...prev, [name]: checked }));
        } else {
            setForm((prev) => ({ ...prev, [name]: value }));
        }
    };

    const handleSelect = (name: string, value: string) => {
        if (name === 'type' && value !== 'Combo') {
            setComboItems([]);
        }
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const handleToggleComboItem = (product: ProductOption) => {
        const exists = comboItems.find((i) => i.item_product_id === product.id);
        if (exists) {
            setComboItems((prev) => prev.filter((i) => i.item_product_id !== product.id));
        } else {
            setComboItems((prev) => [
                ...prev,
                {
                    item_product_id: product.id,
                    item_product_name: product.name,
                    quantity: '1',
                    unit_cost: product.product_cost ?? '0',
                    unit_price: '0',
                    wastage_percent: '0',
                },
            ]);
        }
    };

    const handleComboItemChange = (id: number, field: keyof Omit<ComboItem, 'item_product_id' | 'item_product_name'>, value: string) => {
        setComboItems((prev) => prev.map((i) => (i.item_product_id === id ? { ...i, [field]: value } : i)));
    };

    const computeSubTotal = (item: ComboItem) => {
        const qty = parseFloat(item.quantity) || 0;
        const price = parseFloat(item.unit_price) || 0;
        return (qty * price).toFixed(4);
    };

    const filteredProducts = allProducts.filter(
        (p) => p.name.toLowerCase().includes(comboSearch.toLowerCase()) && !comboItems.some((i) => i.item_product_id === p.id),
    );

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const data = new FormData();

        Object.entries(form).forEach(([key, val]) => {
            if (val === null || val === undefined) return;
            if (typeof val === 'boolean') {
                data.append(key, val ? '1' : '0');
            } else if (val instanceof File) {
                data.append(key, val);
            } else if (val !== '') {
                data.append(key, String(val));
            }
        });

        comboItems.forEach((item, idx) => {
            data.append(`combo_items[${idx}][item_product_id]`, String(item.item_product_id));
            data.append(`combo_items[${idx}][quantity]`, item.quantity);
            data.append(`combo_items[${idx}][unit_cost]`, item.unit_cost);
            data.append(`combo_items[${idx}][unit_price]`, item.unit_price);
            data.append(`combo_items[${idx}][wastage_percent]`, item.wastage_percent);
        });

        router.post(route('product.products.store'), data, {
            forceFormData: true,
            onSuccess: () => toast.success('Product created successfully.'),
            onError: (errors) => setFormErrors(errors),
        });
    };

    return (
        <AppLayout
            title="Add Product"
            breadcrumbs={breadcrumbs}
            actions={
                <Button variant="outline" onClick={() => router.visit(route('product.products.index'))}>
                    Cancel
                </Button>
            }
        >
            <form onSubmit={handleSubmit} className="space-y-6">
                {/* Basic Information */}
                <Card>
                    <CardHeader>
                        <CardTitle>Basic Information</CardTitle>
                    </CardHeader>
                    <CardContent className="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div className="md:col-span-3">
                            <Label htmlFor="name">Name *</Label>
                            <Input name="name" value={form.name} onChange={handleChange} required />
                            {formErrors.name && <p className="text-sm text-red-500">{formErrors.name}</p>}
                        </div>
                        <div>
                            <Label>Type *</Label>
                            <Select value={form.type} onValueChange={(v) => handleSelect('type', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select type" />
                                </SelectTrigger>
                                <SelectContent>
                                    {productTypes.map((t) => (
                                        <SelectItem key={t.value} value={t.value}>
                                            {t.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {formErrors.type && <p className="text-sm text-red-500">{formErrors.type}</p>}
                        </div>
                        <div>
                            <Label htmlFor="code">Code</Label>
                            <Input name="code" value={form.code} onChange={handleChange} />
                            {formErrors.code && <p className="text-sm text-red-500">{formErrors.code}</p>}
                        </div>
                        <div>
                            <Label>Barcode Symbology</Label>
                            <Select value={form.barcode_symbology} onValueChange={(v) => handleSelect('barcode_symbology', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select symbology" />
                                </SelectTrigger>
                                <SelectContent>
                                    {barcodeSymbologies.map((b) => (
                                        <SelectItem key={b.value} value={b.value}>
                                            {b.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {formErrors.barcode_symbology && <p className="text-sm text-red-500">{formErrors.barcode_symbology}</p>}
                        </div>
                    </CardContent>
                </Card>

                {/* Combo Items */}
                {form.type === 'Combo' && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Combo Items</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <Label>Search Products</Label>
                                <Input value={comboSearch} onChange={(e) => setComboSearch(e.target.value)} placeholder="Search by product name…" />
                            </div>
                            {comboSearch && filteredProducts.length > 0 && (
                                <div className="max-h-48 overflow-y-auto rounded border">
                                    {filteredProducts.map((p) => (
                                        <button
                                            key={p.id}
                                            type="button"
                                            onClick={() => handleToggleComboItem(p)}
                                            className="flex w-full items-center gap-2 px-3 py-2 text-left text-sm hover:bg-muted"
                                        >
                                            <span className="flex-1">{p.name}</span>
                                            <span className="text-muted-foreground">Cost: {p.product_cost ?? '—'}</span>
                                        </button>
                                    ))}
                                </div>
                            )}

                            {comboItems.length > 0 && (
                                <div className="overflow-x-auto">
                                    <table className="w-full text-sm">
                                        <thead>
                                            <tr className="border-b text-left text-muted-foreground">
                                                <th className="pr-3 pb-2 font-medium">Product</th>
                                                <th className="pr-3 pb-2 font-medium">Qty</th>
                                                <th className="pr-3 pb-2 font-medium">Unit Cost</th>
                                                <th className="pr-3 pb-2 font-medium">Unit Price</th>
                                                <th className="pr-3 pb-2 font-medium">Wastage %</th>
                                                <th className="pr-3 pb-2 font-medium">Sub Total</th>
                                                <th className="pb-2 font-medium"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {comboItems.map((item) => (
                                                <tr key={item.item_product_id} className="border-b">
                                                    <td className="py-2 pr-3">{item.item_product_name}</td>
                                                    <td className="py-2 pr-3">
                                                        <Input
                                                            type="number"
                                                            step="0.0001"
                                                            min="0.0001"
                                                            value={item.quantity}
                                                            onChange={(e) => handleComboItemChange(item.item_product_id, 'quantity', e.target.value)}
                                                            className="h-7 w-20 text-sm"
                                                        />
                                                    </td>
                                                    <td className="py-2 pr-3">
                                                        <Input
                                                            type="number"
                                                            step="0.0001"
                                                            min="0"
                                                            value={item.unit_cost}
                                                            onChange={(e) => handleComboItemChange(item.item_product_id, 'unit_cost', e.target.value)}
                                                            className="h-7 w-24 text-sm"
                                                        />
                                                    </td>
                                                    <td className="py-2 pr-3">
                                                        <Input
                                                            type="number"
                                                            step="0.0001"
                                                            min="0"
                                                            value={item.unit_price}
                                                            onChange={(e) =>
                                                                handleComboItemChange(item.item_product_id, 'unit_price', e.target.value)
                                                            }
                                                            className="h-7 w-24 text-sm"
                                                        />
                                                    </td>
                                                    <td className="py-2 pr-3">
                                                        <Input
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            max="100"
                                                            value={item.wastage_percent}
                                                            onChange={(e) =>
                                                                handleComboItemChange(item.item_product_id, 'wastage_percent', e.target.value)
                                                            }
                                                            className="h-7 w-20 text-sm"
                                                        />
                                                    </td>
                                                    <td className="py-2 pr-3 text-muted-foreground">{computeSubTotal(item)}</td>
                                                    <td className="py-2">
                                                        <button
                                                            type="button"
                                                            onClick={() =>
                                                                setComboItems((prev) =>
                                                                    prev.filter((i) => i.item_product_id !== item.item_product_id),
                                                                )
                                                            }
                                                            className="text-muted-foreground hover:text-red-500"
                                                        >
                                                            <X className="h-4 w-4" />
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            )}
                            {formErrors['combo_items'] && <p className="text-sm text-red-500">{formErrors['combo_items']}</p>}
                        </CardContent>
                    </Card>
                )}

                {/* Classification */}
                <Card>
                    <CardHeader>
                        <CardTitle>Classification</CardTitle>
                    </CardHeader>
                    <CardContent className="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <Label>Category</Label>
                            <Select value={form.product_category_id} onValueChange={(v) => handleSelect('product_category_id', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select category" />
                                </SelectTrigger>
                                <SelectContent>
                                    {categories.map((c) => (
                                        <SelectItem key={c.id} value={String(c.id)}>
                                            {c.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label>Brand</Label>
                            <Select value={form.product_brand_id} onValueChange={(v) => handleSelect('product_brand_id', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select brand" />
                                </SelectTrigger>
                                <SelectContent>
                                    {brands.map((b) => (
                                        <SelectItem key={b.id} value={String(b.id)}>
                                            {b.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label>Base Unit</Label>
                            <Select value={form.product_unit_id} onValueChange={(v) => handleSelect('product_unit_id', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select unit" />
                                </SelectTrigger>
                                <SelectContent>
                                    {units.map((u) => (
                                        <SelectItem key={u.id} value={String(u.id)}>
                                            {u.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label>Sale Unit</Label>
                            <Select value={form.product_sale_unit_id} onValueChange={(v) => handleSelect('product_sale_unit_id', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select sale unit" />
                                </SelectTrigger>
                                <SelectContent>
                                    {units.map((u) => (
                                        <SelectItem key={u.id} value={String(u.id)}>
                                            {u.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label>Purchase Unit</Label>
                            <Select value={form.product_purchase_unit_id} onValueChange={(v) => handleSelect('product_purchase_unit_id', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select purchase unit" />
                                </SelectTrigger>
                                <SelectContent>
                                    {units.map((u) => (
                                        <SelectItem key={u.id} value={String(u.id)}>
                                            {u.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    </CardContent>
                </Card>

                {/* Pricing */}
                <Card>
                    <CardHeader>
                        <CardTitle>Pricing</CardTitle>
                    </CardHeader>
                    <CardContent className="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <Label htmlFor="product_cost">Cost</Label>
                            <Input name="product_cost" type="number" step="0.0001" min="0" value={form.product_cost} onChange={handleChange} />
                            {formErrors.product_cost && <p className="text-sm text-red-500">{formErrors.product_cost}</p>}
                        </div>
                        <div>
                            <Label>Profit Margin Type</Label>
                            <Select value={form.profit_margin_type} onValueChange={(v) => handleSelect('profit_margin_type', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select type" />
                                </SelectTrigger>
                                <SelectContent>
                                    {profitMarginTypes.map((t) => (
                                        <SelectItem key={t.value} value={t.value}>
                                            {t.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label htmlFor="profit_margin">Profit Margin</Label>
                            <Input name="profit_margin" type="number" step="0.0001" min="0" value={form.profit_margin} onChange={handleChange} />
                            {formErrors.profit_margin && <p className="text-sm text-red-500">{formErrors.profit_margin}</p>}
                        </div>
                        <div>
                            <Label htmlFor="product_price">Selling Price</Label>
                            <Input name="product_price" type="number" step="0.0001" min="0" value={form.product_price} onChange={handleChange} />
                            {formErrors.product_price && <p className="text-sm text-red-500">{formErrors.product_price}</p>}
                        </div>
                        <div>
                            <Label htmlFor="wholesale_price">Wholesale Price</Label>
                            <Input name="wholesale_price" type="number" step="0.0001" min="0" value={form.wholesale_price} onChange={handleChange} />
                            {formErrors.wholesale_price && <p className="text-sm text-red-500">{formErrors.wholesale_price}</p>}
                        </div>
                        <div>
                            <Label htmlFor="daily_sale_objective">Daily Sale Objective</Label>
                            <Input
                                name="daily_sale_objective"
                                type="number"
                                step="0.0001"
                                min="0"
                                value={form.daily_sale_objective}
                                onChange={handleChange}
                            />
                            {formErrors.daily_sale_objective && <p className="text-sm text-red-500">{formErrors.daily_sale_objective}</p>}
                        </div>
                    </CardContent>
                </Card>

                {/* Tax */}
                <Card>
                    <CardHeader>
                        <CardTitle>Tax</CardTitle>
                    </CardHeader>
                    <CardContent className="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <Label>Product Tax</Label>
                            <Select value={form.product_tax} onValueChange={(v) => handleSelect('product_tax', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select tax" />
                                </SelectTrigger>
                                <SelectContent>
                                    {productTaxes.map((t) => (
                                        <SelectItem key={t.value} value={t.value}>
                                            {t.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label>Tax Method</Label>
                            <Select value={form.tax_method} onValueChange={(v) => handleSelect('tax_method', v)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select method" />
                                </SelectTrigger>
                                <SelectContent>
                                    {taxMethods.map((t) => (
                                        <SelectItem key={t.value} value={t.value}>
                                            {t.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    </CardContent>
                </Card>

                {/* Warranty & Guarantee */}
                <Card>
                    <CardHeader>
                        <CardTitle>Warranty & Guarantee</CardTitle>
                    </CardHeader>
                    <CardContent className="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div className="flex items-end gap-2">
                            <div className="flex-1">
                                <Label htmlFor="warranty_value">Warranty</Label>
                                <Input
                                    name="warranty_value"
                                    type="number"
                                    min="0"
                                    value={form.warranty_value}
                                    onChange={handleChange}
                                    placeholder="Value"
                                />
                                {formErrors.warranty_value && <p className="text-sm text-red-500">{formErrors.warranty_value}</p>}
                            </div>
                            <div className="w-32">
                                <Select value={form.warranty_duration_type} onValueChange={(v) => handleSelect('warranty_duration_type', v)}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Unit" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {durationTypes.map((d) => (
                                            <SelectItem key={d.value} value={d.value}>
                                                {d.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div className="flex items-end gap-2">
                            <div className="flex-1">
                                <Label htmlFor="guarantee_value">Guarantee</Label>
                                <Input
                                    name="guarantee_value"
                                    type="number"
                                    min="0"
                                    value={form.guarantee_value}
                                    onChange={handleChange}
                                    placeholder="Value"
                                />
                                {formErrors.guarantee_value && <p className="text-sm text-red-500">{formErrors.guarantee_value}</p>}
                            </div>
                            <div className="w-32">
                                <Select value={form.guarantee_duration_type} onValueChange={(v) => handleSelect('guarantee_duration_type', v)}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Unit" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {durationTypes.map((d) => (
                                            <SelectItem key={d.value} value={d.value}>
                                                {d.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Flags */}
                <Card>
                    <CardHeader>
                        <CardTitle>Options</CardTitle>
                    </CardHeader>
                    <CardContent className="grid grid-cols-2 gap-4 md:grid-cols-4">
                        {(
                            [
                                { name: 'is_featured', label: 'Featured' },
                                { name: 'has_batch_and_expire_date', label: 'Batch & Expiry' },
                                { name: 'has_imei_or_serial_no', label: 'IMEI / Serial No' },
                                { name: 'has_promotional_price', label: 'Promotional Price' },
                            ] as const
                        ).map(({ name, label }) => (
                            <div key={name} className="flex items-center gap-2">
                                <Checkbox
                                    id={name}
                                    checked={form[name]}
                                    onCheckedChange={(checked) => setForm((prev) => ({ ...prev, [name]: !!checked }))}
                                />
                                <Label htmlFor={name}>{label}</Label>
                            </div>
                        ))}
                    </CardContent>
                </Card>

                {/* Media & Details */}
                <Card>
                    <CardHeader>
                        <CardTitle>Media & Details</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div>
                            <Label htmlFor="product_image">Product Image</Label>
                            <Input name="product_image" type="file" accept="image/*" onChange={handleChange} />
                            {formErrors.product_image && <p className="text-sm text-red-500">{formErrors.product_image}</p>}
                        </div>
                        <div>
                            <Label htmlFor="embedded_barcode">Embedded Barcode</Label>
                            <Input name="embedded_barcode" value={form.embedded_barcode} onChange={handleChange} />
                        </div>
                        <div>
                            <Label htmlFor="product_details">Product Details</Label>
                            <textarea
                                name="product_details"
                                value={form.product_details}
                                onChange={handleChange}
                                rows={4}
                                className="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                            />
                        </div>
                    </CardContent>
                </Card>

                <div className="flex justify-end gap-3 pb-6">
                    <Button type="button" variant="outline" onClick={() => router.visit(route('product.products.index'))}>
                        Cancel
                    </Button>
                    <Button type="submit">Create Product</Button>
                </div>
            </form>
        </AppLayout>
    );
}
