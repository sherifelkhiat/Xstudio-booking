# Xstudio-booking
XBooking facilitates seamless integration with the Product functionality within your Bagisto system. It provides endpoints and methods to interact with and harness the capabilities of a comprehensive booking system, enabling smooth communication and utilization of booking-related features in your applications.

Requirements:
Bagisto: v2.1.x
Installation:
To install the Booking Product Extension, follow these steps:

1. Unzip the respective extension zip and then merge "packages/Webkul" folders into project root directory.
2. Open the composer.json file and add the following line under the 'psr-4' section:
"Webkul\\Xbooking\\": "packages/Webkul/Xbooking/src"
3. In the config/app.php file, add the following line under the 'providers' section:
Webkul\Xbooking\Providers\XbookingServiceProvider::class,
5. Run the following commands to complete the setup:
composer dump-autoload
php artisan migrate
After following these steps, the Booking Product Extension should be successfully installed and ready for use in your Bagisto v2.1.x project.