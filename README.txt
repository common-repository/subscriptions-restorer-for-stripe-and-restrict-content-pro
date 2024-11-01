=== Subscriptions Restorer for Stripe and Restrict Content Pro ===
Contributors: WPSpin
Tags: stripe, subscriptions, restore, canceled
Requires at least: 5.0
Tested up to: 6.5.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Restore cancelled Stripe subscriptions in Restrict Content Pro easily. Prevent revenue loss and ensure seamless access with bulk restoration options.

== Description ==

Stripe Subscription Restorer for Restrict Content Pro is an essential tool designed to tackle specific challenges faced by users of Restrict Content Pro with active Stripe subscriptions. This plugin becomes indispensable when a staging copy of a live website accidentally retains active Stripe webhook connections, leading to unintentional cancellations of live subscriptions when modifications are made on the staging environment.
= Who Should Use This Plugin? This plugin is ideal for you if: =
1.	You utilize Restrict Content Pro to manage member subscriptions.
2.	Your subscribers make recurring payments through Stripe.
3.	You have experienced accidental removal of Stripe subscriptions — whether directly through the Stripe dashboard, via operations on a staging site, or other scenarios that inadvertently cancel recurring payment invoices.
4.	You still retain active subscriber records within Restrict Content Pro.
= Key Features: =
•	Easy Recovery: Quickly restores cancelled Stripe subscriptions by creating new ones that sync with existing Restrict Content Pro subscriptions.
•	Bulk Restoration Options: Allows for bulk restoration of multiple subscriptions, minimizing the need for tedious, individual fixes.
•	Seamless Integration: Integrates smoothly with Stripe and Restrict Content Pro, ensuring that your subscription management remains flawless.
= Instructions for Use: =
1.	API Key Entry: Begin by entering your Stripe API key to securely connect your Stripe account.
2.	View Cancelled Subscriptions: Easily view a list of all cancelled subscriptions that require restoration.
3.	Bulk Restore Functionality: Utilize the bulk option to select and restore multiple subscriptions simultaneously.
4.	Subscription Continuity: Restored subscriptions are reinitiated with a new start date and preserved to end on the original end date, ensuring uninterrupted access for your users.
= Benefits: =
•	Prevent Loss of Revenue: Safeguard your revenue stream by ensuring that accidental cancellations do not lead to a loss of paying subscribers.
•	Maintain Subscriber Satisfaction: Keep your subscribers satisfied by preventing unexpected disruptions to their subscription access.
•	Efficiency and Convenience: The bulk restoration feature saves time and reduces the hassle associated with managing subscription errors.
This plugin is a must-have for any Restrict Content Pro user who integrates Stripe for payment processing, providing a reliable safety net against common operational mishaps in subscription management.


== Usage of Third-Party Services ==

This plugin relies on the Stripe API to manage and restore canceled subscriptions. The following endpoints are used:

* `https://api.stripe.com/v1/customers`
* `https://api.stripe.com/v1/subscriptions`

Your data will be sent to these Stripe endpoints under the following circumstances:

1. To fetch information about canceled subscriptions.
2. To fetch customer details associated with the subscriptions.
3. To create new subscriptions based on previously canceled subscriptions.

Please ensure you have reviewed Stripe's terms of use and privacy policies:

* [Stripe Terms of Use](https://stripe.com/legal)
* [Stripe Privacy Policy](https://stripe.com/privacy)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/rcp-restore-cancelled-stripe-subscriptions` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings->RCP restore canceled stripe subscriptions screen to configure the plugin.

== Screenshots ==

1. Begin by entering your Stripe API key to securely connect your Stripe account.
2. Easily view a list of all cancelled subscriptions that require restoration.
3. Bulk Restore Functionality: Utilize the bulk option to select and restore multiple subscriptions simultaneously.
4. Restored subscriptions are reinitiated with a new start date and preserved to end on the original end date, ensuring uninterrupted access for your users.

== Changelog ==

= 1.0.0 =
* Initial release
