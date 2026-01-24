# cPanel Deployment Guide (GitHub Workflow)

This guide explains how to deploy your Laravel Video Call application to cPanel using GitHub.

## 1. Prerequisites: Build Assets Locally
Since shared hosting often cannot run `npm run build`, you must build locally and commit the assets.

1.  **Run Build**:
    ```bash
    npm run build
    ```
2.  **Commit Assets**:
    Ensure `/public/build` is **NOT** in your `.gitignore` (we removed it).
    ```bash
    git add public/build
    git commit -m "Build assets for production"
    git push origin main
    ```

## 2. Deploying on cPanel

1.  **Pull Changes**:
    *   Navigate to your project folder in cPanel (via Terminal or SSH).
    *   Run:
        ```bash
        git pull origin main
        ```
    *   This will bring down your `app.css`, `app.js`, and `manifest.json`.

2.  **Dependencies**:
    *   Run `composer install --optimize-autoloader --no-dev` if you haven't recently.
    *   (Optional) If you have a critical update, you might need `php artisan migrate`.

## 3. Configuration

### Environment (.env)
*   Ensure `APP_URL=https://your-domain.com`.
*   Ensure database credentials are correct.
*   **Permissions**: `storage` and `bootstrap/cache` must be `775`.

### Symlink
If images aren't loading, recreate the storage link:
```bash
php artisan storage:link
```

## 4. Running WebSockets (Cron Job)

On shared hosting, use a **Cron Job** to keep the WebSocket server alive.

1.  **Add Cron Job**: `One Per Minute` (`* * * * *`)
2.  **Command**:
    ```bash
    cd /path/to/your/project && /usr/local/bin/php artisan websockets:serve >> /dev/null 2>&1
    ```
    *   Replace `/path/to/your/project` with your real path.
    *   Use the full path to PHP (ask host if unsure).

### Troubleshooting
*   **ViteManifestNotFoundException**: This means `public/build/manifest.json` is missing. **Run Step 1 again** (Build & Commit/Push).
