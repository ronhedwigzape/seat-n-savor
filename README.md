# seat-n-savor

A restaurant booking system made with Vue 3 and PHP

---
## Development Setup
Here are the steps to set up the development environment for this project:

1. Download and install
   [XAMPP](https://www.apachefriends.org/download.html)
   and [NodeJS](https://nodejs.org/en/),
   if you haven't already.

2. Start Apache and MySQL through XAMPP if not already running.

3. Clone or download this repository to your XAMPP **htdocs** folder.
   The final path should be `path_to/xampp/htdocs/seat-n-savor`.

4. Copy [**`app/config/config.example.php`**](app/config/config.example.php)
   to **`app/config/config.php`**, then modify the database connection settings in the new file.

5. Inside [phpMyAdmin](http://localhost/phpmyadmin),
   create a MySQL database named `seat-n-savor` and import [seat-n-savor.sql](seat-n-savor.sql) into it.

6. Open the terminal and navigate to the project directory **seat-n-savor**.

7. Execute the following commands to install the required dependencies:
   ```sh
   npm install
   ```

8. Compile and run the development server with hot reloading:
   ```sh
   npm run dev
   ```

9. Open your web browser and access <http://localhost:5001/seat-n-savor/> to view the application.

---
## API Setup

Follow these steps to set up the APIs for this project:

### Google reCAPTCHA

1. Sign up for free at [**`reCAPTCHA | Google for Developers`**](https://developers.google.com/recaptcha/), if you haven't already. Don't worry, Google provides this service for free.

2. After signing up, **Go to Settings** and create a label for the reCAPTCHA (preferably the project name).

3. Add `localhost` as the domain for the project. Obtain your `SITE_KEY` and `SECRET_KEY`.

4. In the **index.html** file, replace the placeholder `SITE_KEY` with your actual `SITE_KEY` at [**line #7**](index.html#L7) in the following line of code:
   ```javascript
   <script src="https://www.google.com/recaptcha/api.js?render=SITE_KEY"></script>
   ```

5. In your **`app/config/config.php`**, replace the placeholder `YOUR_SECRET_KEY` with your actual `SECRET_KEY` at [**line #41**](app/config/config.example.php#L41) in the following line of code:
   ```php
   const SECRET_KEY = 'YOUR_SECRET_KEY';
   ```
   
### Vonage SMS API

1. Sign up for free at [**`Vonage`**](https://ui.idp.vonage.com/ui/auth/registration), if you haven't already. Vonage has €2.00 for free trial after signup. 

2. After signing up, login and copy your **API key** and **API Secret** 

3. In your **`app/config/config.php`**, replace the placeholder `YOUR_VONAGE_API_KEY` with your actual `VONAGE_API_KEY` at [**line #42**](app/config/config.example.php#L42) in the following line of code:
   ```php
   const VONAGE_API_KEY = 'YOUR_VONAGE_API_KEY';
   ```

4. Similarly, replace the placeholder `YOUR_VONAGE_API_SECRET` with your actual `VONAGE_API_SECRET` at [**line #43**](app/config/config.example.php#L43) in the following line of code:
   ```php
   const VONAGE_API_SECRET = 'YOUR_VONAGE_API_SECRET';
   ```

By following these steps, you will have the necessary API keys configured for your project.

---
## Production Deployment
Here's how to compile the project for production deployment:

1. Generate the public folder by running the following command:
   ```sh
   npm run build
   ```

2. Access the application by visiting `http://[host_name]/seat-n-savor`,
   where `host_name` is the **IP address** or **host name** of the server in the network.
   For example:
     - <http://localhost/seat-n-savor>
     - <http://192.168.1.99/seat-n-savor>
