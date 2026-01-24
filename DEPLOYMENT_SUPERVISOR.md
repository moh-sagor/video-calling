# Setting up Supervisor for WebSockets

To keep the `php artisan websockets:serve` command running permanently on your server (even if it crashes or the server restarts), you should use **Supervisor**.

## 1. Install Supervisor
If you are on a Linux server (Ubuntu/Debian):
```bash
sudo apt-get install supervisor
```

## 2. Create a Configuration File
Create a new configuration file for your project:
```bash
sudo nano /etc/supervisor/conf.d/laravel-websockets.conf
```

Paste the following content (adjust paths and user!):

```ini
[program:laravel-websockets]
process_name=%(program_name)s_%(process_num)02d
# CUSTOMIZE THIS PATH:
command=php /path/to/your/project/artisan websockets:serve
autostart=true
autorestart=true
# CUSTOMIZE THIS USER:
user=your_linux_username
numprocs=1
redirect_stderr=true
# CUSTOMIZE THIS LOG PATH:
stdout_logfile=/path/to/your/project/storage/logs/websockets.log
```

## 3. Start Supervisor
Run these commands to load the new config and start the process:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-websockets:*
```

## 4. Check Status
To check if it's running:
```bash
sudo supervisorctl status
```

Now your WebSockets server will run automatically in the background!
