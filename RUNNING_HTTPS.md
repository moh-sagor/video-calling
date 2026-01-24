# Running with HTTPS (Required for Camera/Mic)

Browsers block camera and microphone access on "insecure" origins (like `http://192.168.x.x`). To test on other devices (like your phone), you must use **HTTPS**.

## Option 1: Use Ngrok (Recommended)
Ngrok creates a secure tunnel from the internet to your local machine.

1.  **Install Ngrok**:
    - Go to [ngrok.com](https://ngrok.com) and sign up (it's free).
    - Follow instructions to install and authenticate.

2.  **Start your Laravel Server**:
    ```bash
    php artisan serve
    ```
    (It usually runs on port 8000)

3.  **Start Ngrok Tunnel**:
    Open a new terminal and run:
    ```bash
    ngrok http 8000
    ```

4.  **Update .env**:
    Copy the `https://....ngrok-free.app` URL provided by ngrok.
    Open your `.env` file and update:
    ```env
    APP_URL=https://your-ngrok-url.ngrok-free.app
    ```
    (This is important for some asset loading, though WebRTC might work without it if paths are relative).

    > **Troubleshooting**: If you get an `ERR_NGROK_123` error, check your email inbox to verify your Ngrok account!

5.  **Access the URL**:
    Open the `https://....ngrok-free.app` URL on your phone or other computer. Camera access should now work!

## Option 2: LocalTunnel (No Account Required)
If different from Ngrok, you can use `localtunnel` which doesn't require an account.

1.  **Start your Laravel Server**: `php artisan serve`
2.  **Run LocalTunnel**:
    ```bash
    npx localtunnel --port 8000
    ```
3.  **Use the URL**: Copy the URL it gives you (e.g., `https://funny-cat-42.loca.lt`).
    *Note: When you first open the URL, you might need to enter a public IP password. It will give you a link to find it.*

## Option 3: Localhost (PC only)
If you are just testing on the SAME computer, use `http://localhost:8000`. Browsers treat `localhost` as secure.
