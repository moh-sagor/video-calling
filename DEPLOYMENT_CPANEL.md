# cPanel Deployment for WebSockets

On shared hosting (cPanel), you often cannot install "Supervisor". The best workaround is to use a **Cron Job** that attempts to start the server every minute.

## How it works
The `websockets:serve` command runs forever. If it crashes or the server kills it, it stops.
We will set up a Cron Job to run every minute.
- If the server is **already running**, the new command will fail to open port 6001 and exit immediately (safe).
- If the server is **down**, the new command will start it up successfully.

## Steps

1.  **Log in to cPanel**
2.  Go to **Cron Jobs**.
3.  Add a **New Cron Job**:
    *   **Common Settings**: `Once Per Minute` (`* * * * *`)
    *   **Command**:
        ```bash
        cd /path/to/your/project && php artisan websockets:serve >> /dev/null 2>&1
        ```

### Important:
*   Replace `/path/to/your/project` with the **real full path** to your Laravel folder (e.g., `/home/username/public_html/video_app`).
*   You might need to use the full path to PHP, e.g., `/usr/local/bin/php` instead of just `php`. You can ask your host for this path.

## Alternative: SSH
If you have SSH access, you can try running it manually inside a `screen` session:
1.  Login via SSH.
2.  Run `screen`.
3.  Run `php artisan websockets:serve`.
4.  Press `Ctrl + A`, then `D` to detach (keep it running in background).
