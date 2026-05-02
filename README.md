# H2-ERP

**H2-ERP** is a community-driven, open-source SaaS ERP.

> ⚠️ This project is under active development. Features may be incomplete and subject to change.

---

## Tech Stack

- **Backend:** Laravel 12 (PHP)
- **Frontend:** React 19, TypeScript 5
- **Bridge:** Inertia.js v2
- **Styling:** Tailwind CSS 4, shadcn/ui
- **Permissions:** Spatie Permission
- **Multi-tenancy:** Stancl Tenancy
- **Database:** MySQL
- **Build Tool:** Vite

---

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- Node.js 20+
- MySQL

### Installation

```bash
git clone https://github.com/devghor/h2-erp.git
cd h2-erp

composer install
npm install

cp .env.example .env
php artisan key:generate
