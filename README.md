# NexPort Global

Premium import/export commerce platform built with Laravel 12, server-rendered Blade templates, PHP and MySQL.

## Local setup

```bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Open `http://127.0.0.1:8000`. The admin operations overview is at `/admin`.

Demo shipment tracking number: `NPG-78245190`  
Seeded administrator: `admin@nexport.test` / `password` (development only)

The application uses the MySQL database `import_export`. Connection settings are stored in `.env`.

## Included modules

- Responsive server-rendered corporate storefront and product catalogue
- Product search, category filtering and price sorting
- Product details and product-specific WhatsApp enquiries
- Quotation, custom import request and contact workflows
- Shipment lookup with status timeline
- Laravel controllers, Blade components, validation and request rate limits
- Seeded product and shipment demo data
- Admin operations/KPI overview
- Dynamic product details with zoomable multi-image galleries and related products
- Product administration at `/admin/products` with pricing, shipping, SEO, category-specific specifications and reorderable images
