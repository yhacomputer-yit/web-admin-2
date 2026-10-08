# Hostinger deployment (V3-Test)

Every push to `V3-Test` runs [`.github/workflows/deploy-hostinger.yml`](.github/workflows/deploy-hostinger.yml). The workflow installs production Composer dependencies, builds Vite assets, checks PHP syntax, then deploys to Hostinger over **SSH + rsync**. No FTP account or FTP secret is required.

## One-time GitHub setup

In **Repository → Settings → Secrets and variables → Actions**, add these repository secrets:

| Secret | Value |
|---|---|
| `HOSTINGER_SSH_HOST` | Hostinger SSH hostname or IP from hPanel |
| `HOSTINGER_SSH_PORT` | SSH port from hPanel, commonly `65002` |
| `HOSTINGER_SSH_USERNAME` | Hostinger SSH username |
| `HOSTINGER_SSH_PRIVATE_KEY` | Base64-encoded private key; instructions below |
| `HOSTINGER_REMOTE_PATH` | Absolute project path, for example `/home/u123456789/domains/example.com/public_html/` |

### Encode the private key for GitHub

The workflow expects the private key as one Base64 line. This avoids Windows line-ending and multiline secret formatting issues. In PowerShell, run:

```powershell
[Convert]::ToBase64String(
  [IO.File]::ReadAllBytes("$env:USERPROFILE\.ssh\hostinger_github_actions")
) | Set-Clipboard
```

Update the GitHub secret named `HOSTINGER_SSH_PRIVATE_KEY` and paste the clipboard value as-is. It should be one long line. Do **not** paste the `ssh-ed25519 ...` public key into this secret. Do not add quotes, spaces, or code fences.

## One-time Hostinger setup

1. Enable SSH access in hPanel and add the GitHub Actions public key.
2. Create the MySQL database and user in hPanel.
3. Copy `.env.example` to `.env` **on the server only** and fill in the production values.
4. Set `APP_URL` to the HTTPS domain, `APP_DEBUG=false`, and use the real database credentials.
5. Generate an application key once on the server with `php artisan key:generate` if `APP_KEY` is empty.
6. Ensure `storage` and `bootstrap/cache` are writable by PHP.
7. Enable Hostinger SSL for the domain. The committed `.htaccess` files redirect HTTP to HTTPS; the workflow does not issue or renew certificates.
8. Run migrations manually when a release requires them: `php artisan migrate --force`.

The workflow intentionally excludes `.env`, tests, Node modules, logs, and cache files. `vendor/` and `public/build/` are generated in GitHub Actions and synced over SSH, so they no longer need to be committed or manually uploaded.

## SSH test

Before relying on Actions, test the same account from a terminal:

```bash
ssh -p 65002 USERNAME@HOSTINGER_SSH_HOST
cd /home/USERNAME/domains/example.com/public_html
php -v
```

The SSH user must have write permission for `HOSTINGER_REMOTE_PATH` and must be able to run `php artisan` there.

## Important security note

The old tracked `.env` contained a database password. It has been removed from the branch, but Git history still contains the old value. Rotate that database password in Hostinger and update the server `.env` before going live. Never commit production `.env` files or credentials.
