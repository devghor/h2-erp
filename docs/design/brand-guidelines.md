# H2 ERP Brand Guidelines

Source of truth: [`tokens.css`](./tokens.css). Every value below maps to a CSS variable. Use the variable, never the raw hex, in new code.

## 1. Brand essence

H2 ERP is a dense, data-heavy business tool (HR, payroll, leave, product, UAM). The interface stays calm and neutral so data leads. Coral is the single brand voice: used sparingly for primary action, active state, and identity.

Principles:

- **Neutral first.** Gray surfaces carry the UI. Color signals meaning or action, not decoration.
- **One accent.** Coral marks "do this" and "you are here". Nothing else competes with it.
- **Status is semantic.** Color pairs (fill / text / border) communicate state consistently across tables, badges, and alerts.
- **Comfortable density.** 4px grid, 14px base text, 56px rows (44px compact).

## 2. Color

### 2.1 Brand (coral)

| Token | Light | Dark | Use |
|---|---|---|---|
| `--brand-50` | `#FFF6F6` | `#2A1416` | Faint tint backgrounds |
| `--brand-100` | `#FFF1F1` | `#2E1517` | Active nav row, selected menu row |
| `--brand-200` | `#FFE4E5` | `#3D1A1D` | Initials avatar fill |
| `--brand-300` | `#FFCFD1` | n/a | Soft borders, focus halo |
| `--brand-400` | `#FF797D` | n/a | Secondary accents, disabled primary |
| `--brand-500` | `#FF4A50` | `#FF5A60` | **Primary**: buttons, active nav text, logo, step badges |
| `--brand-600` | `#E63A40` | `#FF7277` | Hover |
| `--brand-700` | `#C72D33` | `#FF8A8E` | Pressed |

Rules:

- One primary button per view or dialog.
- Do not use coral for status or charts. Coral reads as "brand/action", and would collide with `danger`.
- Text on `--brand-500` fill is white. Verify contrast before using `--brand-400` or lighter as a fill behind text.

### 2.2 Neutrals

| Token | Light | Dark | Use |
|---|---|---|---|
| `--bg-page` | `#F8FAFC` | `#0B0F17` | App background |
| `--bg-surface` | `#FFFFFF` | `#111827` | Cards, tables, dialogs, sidebar |
| `--bg-subtle` | `#FAFAFA` | `#151C2B` | Table header row |
| `--bg-muted` | `#F3F4F6` | `#1F2937` | Hover fill, disabled fill, chips |
| `--border` | `#E5E7EB` | `#1F2937` | Default dividers and outlines |
| `--border-strong` | `#D1D5DB` | `#374151` | Input borders, emphasized outlines |
| `--text` | `#111827` | `#F3F4F6` | Headings, primary values |
| `--text-body` | `#374151` | `#D1D5DB` | Body copy, table cells |
| `--text-muted` | `#6B7280` | `#9CA3AF` | Secondary labels, helper text |
| `--text-faint` | `#9CA3AF` | `#6B7280` | Placeholders, disabled text |

### 2.3 Status

Each status has three tokens: `-bg` (fill), `-fg` (text/icon), `-bd` (border). Badges use all three.

| Status | Meaning | Examples |
|---|---|---|
| `success` | Completed, approved, paid | Leave approved, payroll paid |
| `active` | Live, in force, enabled | Active employee, active policy |
| `info` | Neutral information, in progress | Processing, informational notice |
| `warn` | Needs attention, caution | Expiring document, incomplete profile |
| `pending` | Awaiting action | Pending approval |
| `late` | Overdue, behind schedule | Late attendance, overdue task |
| `danger` | Error, rejected, destructive | Rejected, failed, delete confirmation |
| `neutral` | No state, archived, draft | Draft, inactive, archived |

Rules:

- Never convey status by color alone. Always pair with a text label or icon.
- Use the matching `-fg` for text on `-bg`. Do not mix tokens across statuses.
- `warn` (yellow) and `pending` (amber) and `late` (orange) are distinct states. Do not collapse them.

### 2.4 Charts

Coral is never a chart color.

| Token | Light | Dark | Use |
|---|---|---|---|
| `--chart-1` | `#2a78d6` | `#3987e5` | Single series, "estimated", primary bars |
| `--chart-2` | `#eb6834` | `#d95926` | Second series, "logged" |
| `--chart-grid` | `#E5E7EB` | `#1F2937` | Gridlines and axes |

Add further series only from the validated reference palette. Keep series count low and label directly where possible.

## 3. Typography

| Token | Value |
|---|---|
| `--font-sans` | Inter, then system UI stack |
| `--font-mono` | JetBrains Mono, then system mono stack |

| Token | Size | Typical use |
|---|---|---|
| `--fs-xs` | 12px | Badges, captions, table meta |
| `--fs-sm` | 13px | Table cells, helper text, secondary buttons |
| `--fs-base` | 14px | **Default body and UI text** |
| `--fs-md` | 16px | Dialog titles, emphasized body |
| `--fs-lg` | 18px | Section headings |
| `--fs-xl` | 22px | Page titles |
| `--fs-2xl` | 28px | Dashboard figures, hero numbers |

Guidance:

- Weights: 400 body, 500 labels and nav, 600 headings and figures.
- Use `--font-mono` for IDs, codes, payroll figures in dense columns, and tokens. Use tabular numerals for aligned numbers.
- Sentence case for all UI text, headings, and buttons. No ALL CAPS except short table-header labels if the design calls for it.

## 4. Spacing

4px grid. Use only these steps.

| Token | px | Token | px |
|---|---|---|---|
| `--sp-1` | 4 | `--sp-6` | 24 |
| `--sp-2` | 8 | `--sp-7` | 28 |
| `--sp-3` | 12 | `--sp-8` | 32 |
| `--sp-4` | 16 | `--sp-10` | 40 |
| `--sp-5` | 20 | | |

Defaults: card padding `--sp-6`; form field gap `--sp-4`; inline icon+label gap `--sp-2`; page gutter `--sp-8`.

## 5. Shape and elevation

| Token | Value | Use |
|---|---|---|
| `--r-sm` | 6px | Inputs, buttons, selects |
| `--r-md` | 8px | Menus, popovers |
| `--r-lg` | 12px | Cards, table containers |
| `--r-full` | 999px | Pills, avatars, step badges |

| Token | Use |
|---|---|
| `--shadow-xs` | Cards and inputs at rest. Subtle. |
| `--shadow-menu` | Menus, popovers, dropdowns, floating layers only |

Prefer borders over shadows for structure. Reserve shadow-menu for overlays.

## 6. Layout shell

| Token | Value |
|---|---|
| `--sidebar-w` | 272px |
| `--topbar-h` | 68px |
| `--row-h` | 56px comfortable, 44px compact |

- Sidebar and topbar sit on `--bg-surface` with `--border` dividers. Page content sits on `--bg-page`.
- Active nav row: `--brand-100` fill with `--brand-500` text.
- Tables: header row on `--bg-subtle`, row dividers `--border`, container radius `--r-lg`. Offer a compact density toggle (44px).

## 7. Components

### Buttons

| Variant | Fill | Text | Hover | Pressed |
|---|---|---|---|---|
| Primary | `--brand-500` | white | `--brand-600` | `--brand-700` |
| Secondary | `--bg-surface`, border `--border-strong` | `--text` | `--bg-muted` | `--bg-muted` |
| Ghost | transparent | `--text-body` | `--bg-muted` | `--bg-muted` |
| Destructive | `--danger-fg` | white | darken one step | darken two steps |

Radius `--r-sm`. Text `--fs-sm` or `--fs-base`, weight 500. Disabled: reduce to `--text-faint` on `--bg-muted`.

### Inputs

`--bg-surface` fill, `--border-strong` outline, `--r-sm`, placeholder `--text-faint`. Focus: `--brand-500` border with `--brand-300` ring. Error: `--danger-fg` border, message in `--danger-fg` at `--fs-xs`.

### Badges and status pills

`--r-full`, `--fs-xs`, weight 500, fill `-bg`, text `-fg`, border `-bd`. Optional leading dot in `-fg`.

### Avatars

Circle (`--r-full`). Initials on `--brand-200` with `--brand-700` text.

### Step badges

Circle, `--brand-500` fill, white number. Completed steps may swap to `success`.

### Menus and popovers

`--bg-surface`, `--border`, `--r-md`, `--shadow-menu`. Selected row `--brand-100`.

### Cards and table containers

`--bg-surface`, `--border`, `--r-lg`, `--shadow-xs`.

## 8. Dark mode

Applied with the `.dark` class (sets `color-scheme: dark`). All semantic tokens are redefined; components must not hardcode hex.

- Surfaces step up in lightness from `--bg-page` to `--bg-surface` to `--bg-muted`.
- Brand coral shifts lighter (`--brand-500` becomes `#FF5A60`) and hover/pressed go lighter, not darker, to keep contrast.
- Status fills become deep tints with bright `-fg` text.
- Shadows are replaced by stronger black shadows on overlays only.
- Test every screen in both themes.

## 9. Accessibility

- Body text meets WCAG AA (4.5:1). `--text-faint` is for placeholders and disabled only, never for required information.
- Visible focus ring on every interactive element.
- Do not rely on color alone for status, chart series, or validation.
- Minimum hit target 32px for dense table actions, 40px elsewhere.

## 10. Voice

- Plain, direct, professional. Say what happened and what to do next.
- Sentence case. Verbs on buttons: "Add employee", "Approve leave", "Save changes".
- Errors name the problem and the fix: "Start date must be before end date."
- Destructive confirmations state the consequence and the count: "Delete 3 branches? This cannot be undone."

## 11. Usage in code

- Tokens live in `docs/design/tokens.css`. The app maps them through Tailwind v4 theme variables and shadcn/ui; keep both in sync when a token changes.
- Prefer token-backed utilities (`bg-surface`, `text-muted`, etc.) over arbitrary values.
- Never hardcode hex for brand, neutral, status, or chart colors in components.
- Add new tokens to `tokens.css` first, with a dark-mode counterpart, then document them here.

## 12. Do and don't

| Do | Don't |
|---|---|
| One coral primary action per view | Multiple coral buttons competing |
| Status badge with label and color | Color-only status dots |
| Neutral gray for chrome and structure | Tinted backgrounds for decoration |
| `--chart-1` / `--chart-2` for data | Coral in charts |
| Border for structure, shadow for overlays | Shadows on every card |
| Tokens everywhere | Raw hex in components |
