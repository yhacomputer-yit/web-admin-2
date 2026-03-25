# Deployment Fix TODO - Laravel on Namecheap

## Completed:
- [x] `npm run build` executed (check terminal output)
- [x] Created root .htaccess with DirectoryIndex and rewrites to public/index.php

## Remaining Steps:
1. **Local Test**: Run `php artisan serve` and visit http://localhost:8000 to verify homepage loads (HomeController@index).

2. **Namecheap Deployment**:
   - ZIP entire project folder and upload/extract to cPanel File Manager -> public_html/
   - Edit `public_html/.env`: Set `APP_URL=https://yourdomain.com`, database creds, `APP_ENV=production`, `APP_DEBUG=false`
   - SSH or Terminal in cPanel: 
     ```
     cd public_html
     composer install --optimize-autoloader --no-dev
     php artisan key:generate
     php artisan migrate (if needed)
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     ```
   - Permissions: `chmod 755 storage bootstrap/cache`, `chmod -R 775 storage/ bootstrap/cache`
   - **Preferred**: In cPanel Domains -> Document Root: set to public_html/public

3. **Verify**:
   - Visit https://yourdomain.com/ - should load homepage, not 404.
   - Check cPanel Error Logs if issues.
   - Ensure PHP 8.2+, extensions: pdo_mysql, fileinfo, etc.

4. **Security**: Remove .env from git, set strong APP_KEY.

Done when root URL loads Laravel homepage.
