# Hostinger deployment (V3-Test)

Every push to `V3-Test` runs [`.github/workflows/deploy-hostinger.yml`](.github/workflows/deploy-hostinger.yml). The workflow installs production Composer dependencies, builds Vite assets, checks PHP syntax, and syncs the project to Hostinger over FTP.

## One-time GitHub setup

In **Repository → Settings → Secrets and variables → Actions**, add these repository secrets:

| Secret | Value |
|---|---|
| `HOSTINGER_FTP_SERVER` | Hostinger FTP hostname, for example `ftp.your-domain.example` |
| `HOSTINGER_FTP_USERNAME` | Hostinger FTP username |
| `HOSTINGER_FTP_PASSWORD` | Hostinger FTP password |
| `HOSTINGER_SERVER_DIR` | Target directory, for example `/public_html/` or `/domains/your-domain.example/public_html/` |

Use the FTP account that is restricted to this website. If Hostinger provides FTPS for the account, change `protocol` and `port` in the workflow to the values shown in hPanel.

## One-time Hostinger setup

1. Create the MySQL database and user in hPanel.
2. Copy `.env.example` to `.env` **on the server only** and fill in the production values.
3. Set `APP_URL` to the HTTPS domain, `APP_DEBUG=false`, and use the real database credentials.
4. Generate an application key once on the server with `php artisan key:generate` if `APP_KEY` is empty.
5. Ensure `storage` and `bootstrap/cache` are writable by PHP.
6. Enable Hostinger SSL for the domain. The committed `.htaccess` files redirect HTTP to HTTPS; the workflow does not attempt to issue or renew certificates.
7. Run migrations manually when a release requires them: `php artisan migrate --force`.

The workflow intentionally excludes `.env`, tests, Node modules, logs, and cache files. `vendor/` and `public/build/` are generated on every deploy, so they no longer need to be committed or manually uploaded.

## Important security note

The old tracked `.env` contained a database password. It is removed from the branch by this deployment change, but Git history still contains the old value. Rotate that database password in Hostinger and update the server `.env` before going live. Never commit production `.env` files or credentials.
